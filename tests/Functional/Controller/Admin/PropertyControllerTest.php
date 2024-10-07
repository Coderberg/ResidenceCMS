<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\Feature;
use App\Entity\Metro;
use App\Entity\Neighborhood;
use App\Entity\Property;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PropertyControllerTest extends WebTestCase
{
    use WebTestHelper;

    /**
     * This test changes the database contents by creating a new Property.
     */
    public function testAdminNewProperty(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/property/new');

        $city = $this->getRepository($client, City::class)
            ->findOneBy(['slug' => 'miami'])->getId();

        $dealType = $this->getRepository($client, DealType::class)
            ->findOneBy([])->getId();

        $category = $this->getRepository($client, Category::class)
            ->findOneBy([])->getId();

        $form = $crawler->selectButton('Create property')->form([
            'property[city]' => $city,
            'property[dealType]' => $dealType,
            'property[category]' => $category,
            'property[property_description][title]' => 'test',
            'property[property_description][meta_description]' => 'test',
            'property[address]' => 'test',
            'property[priority_number]' => '-1',
            'property[property_description][content]' => 'test',
        ]);

        $client->submit($form);

        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }

    public function testAdminEditPhoto(): void
    {
        $client = $this->authAsAdmin($this);

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'test'])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/photo/'.$property.'/edit');
        $this->assertSelectorTextContains('html', 'Upload photos');

        $photo = __DIR__.'/../../../../public/images/bg.jpg';

        $form = $crawler->filter('.js-photo-dropzone')->form();
        $form['file']->upload($photo);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testAdminEditProperty(): void
    {
        $client = $this->authAsAdmin($this);

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'test']);

        $neighborhood = $this->getRepository($client, Neighborhood::class)
            ->findOneBy(['slug' => 'south-beach'])->getId();

        $metroStation = $this->getRepository($client, Metro::class)
            ->findOneBy(['slug' => 'government-center'])->getId();

        $feature = $this->getRepository($client, Feature::class)
            ->findOneBy(['name' => 'Secure parking']);

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/property/'.$property->getId().'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'property[property_description][meta_title]' => 'Custom Meta Title',
            'property[neighborhood]' => $neighborhood,
            'property[metro_station]' => $metroStation,
            'property[features]' => [$feature->getId()],
        ]);

        $client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $crawler = $client->request(Request::METHOD_GET, \sprintf(
            '/en/%s/%s/%d',
            $property->getCity()->getSlug(),
            $property->getSlug(),
            $property->getId())
        );

        $this->assertCount(1, $crawler->filter('.fa-parking'));
        $this->assertSelectorTextContains('title', 'Custom Meta Title');
        $this->assertStringContainsString('Secure parking', $crawler->html());
        $this->assertStringContainsString('Government Center', $crawler->html());
        $this->assertStringContainsString('South Beach', $crawler->html());
    }

    public function testAdminDeletePhoto(): void
    {
        $client = $this->authAsAdmin($this);

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'test'])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/photo/'.$property.'/edit');

        $form = $crawler->selectButton('Delete')->form();
        $client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }

    /*
     * This test changes the database contents by deleting a test Property.
     */
    public function testAdminDeleteProperty(): void
    {
        $client = $this->authAsAdmin($this);

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'test'])->getId();

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
    }
}
