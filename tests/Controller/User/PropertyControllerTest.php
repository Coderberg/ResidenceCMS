<?php

declare(strict_types=1);

namespace App\Tests\Controller\User;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\Property;
use App\Entity\PropertyDescription;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class PropertyControllerTest extends WebTestCase
{
    private const USER = [
        'PHP_AUTH_USER' => 'user',
        'PHP_AUTH_PW' => 'user',
    ];

    public function testIndex()
    {
        $client = static::createClient([], self::USER);

        $crawler = $client->request('GET', '/en/user/property');
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', '/user/account'));
        $this->assertCount(2, $crawler->filter('.property-box-img'));
        $this->assertSelectorTextContains('html', 'My properties (2)');

        $client->request('GET', '/en/admin');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUnpublish()
    {
        $client = static::createClient([], self::USER);
        $crawler = $client->request('GET', '/en/user/property');
        $link = $crawler->filter('.btn-outline-secondary')->first()->link();
        $client->request('GET', $link->getUri());

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

    public function testEditingForbidden()
    {
        $client = static::createClient([], self::USER);

        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'admin']);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['author' => $user]);

        $client->request('GET', sprintf('/en/user/property/%d/unpublish', $property->getId()));
        $this->assertResponseStatusCodeSame(403);

        $client->request('GET', sprintf('/en/user/property/%d/edit', $property->getId()));
        $this->assertResponseStatusCodeSame(403);
    }

    public function testPublish()
    {
        $client = static::createClient([], self::USER);
        $crawler = $client->request('GET', '/en/user/property?state=unpublished');
        $link = $crawler->filter('.btn-outline-secondary')->first()->link();
        $client->request('GET', $link->getUri());

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

    public function testNewProperty()
    {
        $client = static::createClient([], self::USER);

        $crawler = $client->request('GET', '/en/user/property/new');

        $city = $client->getContainer()->get('doctrine')
            ->getRepository(City::class)->findOneBy(['slug' => 'miami'])->getId();

        $dealType = $client->getContainer()->get('doctrine')
            ->getRepository(DealType::class)->findOneBy([])->getId();

        $category = $client->getContainer()->get('doctrine')
            ->getRepository(Category::class)->findOneBy([])->getId();

        $form = $crawler->selectButton('Save changes')->form([
            'property[city]' => $city,
            'property[dealType]' => $dealType,
            'property[category]' => $category,
            'property[property_description][title]' => 'added by user',
            'property[property_description][meta_description]' => 'test',
            'property[address]' => 'test',
            'property[property_description][content]' => 'test',
        ]);

        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testAEditPhoto()
    {
        $client = static::createClient([], self::USER);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'added-by-user'])->getId();

        $crawler = $client->request('GET', '/en/user/photo/'.$property.'/edit');
        $this->assertSelectorTextContains('html', 'Upload photos');

        $photo = __DIR__.'/../../../public/images/bg.jpg';

        $form = $crawler->filter('.js-photo-dropzone')->form();
        $form['file']->upload($photo);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testEditProperty()
    {
        $client = static::createClient([], self::USER);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'added-by-user']);

        $crawler = $client->request('GET', sprintf('/en/user/property/%d/edit', $property->getId()));
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Save changes')->form([
            'property[property_description][meta_title]' => 'Custom Meta Title',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedProperty = $client->getContainer()->get('doctrine')
            ->getRepository(PropertyDescription::class)
            ->findOneBy(['meta_title' => 'Custom Meta Title'])->getProperty();

        $client->request('GET', sprintf('/en/user/property/%d/edit', $editedProperty->getId()));
        $this->assertResponseIsSuccessful();
    }

    public function testDeleteProperty()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'added-by-user'])->getId();

        $crawler = $client->request('GET', '/en/admin/property?sort_by=id');
        $client->submit($crawler->filter('#delete-form-'.$property)->form());

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Property::class)->findOneBy([
                'slug' => 'test',
            ]));
    }
}
