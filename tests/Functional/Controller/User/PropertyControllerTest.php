<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\Property;
use App\Entity\PropertyDescription;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PropertyControllerTest extends WebTestCase
{
    use WebTestHelper;

    public function testIndex(): void
    {
        $client = $this->authAsUser($this);
        $crawler = $client->request(Request::METHOD_GET, '/en/user/property');
        $this->assertResponseIsSuccessful(\sprintf('The %s public URL loads correctly.', '/user/account'));
        $this->assertCount(2, $crawler->filter('.property-box-img'));
        $this->assertSelectorTextContains('html', 'My properties (2)');

        $client->request(Request::METHOD_GET, '/en/admin');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUnpublish(): void
    {
        $client = $this->authAsUser($this);
        $crawler = $client->request(Request::METHOD_GET, '/en/user/property');
        $link = $crawler->filter('.btn-outline-secondary')->first()->link();
        $client->request(Request::METHOD_GET, $link->getUri());

        $this->assertResponseIsSuccessful();
        // asserts that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
    }

    public function testEditingForbidden(): void
    {
        $client = $this->authAsUser($this);

        $user = $this->getUser($client, 'admin');
        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['author' => $user]);

        $client->request(Request::METHOD_GET, \sprintf('/en/user/property/%d/update?state=private', $property->getId()));
        $this->assertResponseStatusCodeSame(419);

        $client->request(Request::METHOD_GET, \sprintf('/en/user/property/%d/edit', $property->getId()));
        $this->assertResponseStatusCodeSame(403);
    }

    public function testPublish(): void
    {
        $client = $this->authAsUser($this);
        $crawler = $client->request(Request::METHOD_GET, '/en/user/property?state=unpublished');
        $link = $crawler->filter('.btn-outline-secondary')->first()->link();
        $client->request(Request::METHOD_GET, $link->getUri());

        $this->assertResponseIsSuccessful();
        // asserts that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
    }

    public function testNewProperty(): void
    {
        $client = $this->authAsUser($this);
        $this->updateSettings($client, ['allow_html' => '0']);

        $crawler = $client->request(Request::METHOD_GET, '/en/user/property/new');

        $city = $this->getRepository($client, City::class)
            ->findOneBy(['slug' => 'miami'])->getId();

        $dealType = $this->getRepository($client, DealType::class)
            ->findOneBy([])->getId();

        $category = $this->getRepository($client, Category::class)
            ->findOneBy([])->getId();

        $form = $crawler->selectButton('Save changes')->form([
            'property[city]' => $city,
            'property[dealType]' => $dealType,
            'property[category]' => $category,
            'property[property_description][title]' => 'added by user',
            'property[property_description][meta_description]' => 'test',
            'property[address]' => 'test',
            'property[property_description][content]' => '<h1>Lorem</h1> <p>ipsum <strong>dolor</strong> sit</p> amet',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects(null, 302);
    }

    public function testEditPhoto(): void
    {
        $client = $this->authAsUser($this);

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'added-by-user'])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/user/photo/'.$property.'/edit');
        $this->assertSelectorTextContains('html', 'Upload photos');

        $photo = __DIR__.'/../../../../public/images/bg.jpg';

        $form = $crawler->filter('.js-photo-dropzone')->form();
        $form['file']->upload($photo);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testEditProperty(): void
    {
        $client = $this->authAsUser($this);

        /**
         * @var Property $property
         */
        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'added-by-user']);

        $this->assertSame('Lorem ipsum dolor sit amet', $property->getPropertyDescription()->getContent());

        $crawler = $client->request(Request::METHOD_GET, \sprintf('/en/user/property/%d/edit', $property->getId()));
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Save changes')->form([
            'property[property_description][meta_title]' => 'Custom Meta Title',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects(\sprintf('/en/user/photo/%d/edit', $property->getId()), 302);

        $editedProperty = $this->getRepository($client, PropertyDescription::class)
            ->findOneBy(['meta_title' => 'Custom Meta Title'])->getProperty();

        $client->request(Request::METHOD_GET, \sprintf('/en/user/property/%d/edit', $editedProperty->getId()));
        $this->assertResponseIsSuccessful();
    }

    public function testDeleteProperty(): void
    {
        $client = $this->authAsAdmin($this);

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'added-by-user'])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/property?sort_by=id');
        $client->submit($crawler->filter('#delete-form-'.$property)->form());

        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $this->assertNull($this->getRepository($client, Property::class)->findOneBy([
            'slug' => 'test',
        ]));

        $this->resetSettings($client);
    }
}
