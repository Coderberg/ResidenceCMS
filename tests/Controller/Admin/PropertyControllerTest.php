<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\Feature;
use App\Entity\Metro;
use App\Entity\Neighborhood;
use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class PropertyControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    /**
     * This test changes the database contents by creating a new Property.
     */
    public function testAdminNewProperty(): void
    {
        $client = static::createClient([], self::SERVER);

        $crawler = $client->request('GET', '/en/admin/property/new');

        $city = $client->getContainer()->get('doctrine')
            ->getRepository(City::class)->findOneBy(['slug' => 'miami'])->getId();

        $dealType = $client->getContainer()->get('doctrine')
            ->getRepository(DealType::class)->findOneBy([])->getId();

        $category = $client->getContainer()->get('doctrine')
            ->getRepository(Category::class)->findOneBy([])->getId();

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

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testAdminEditPhoto(): void
    {
        $client = static::createClient([], self::SERVER);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'test'])->getId();

        $crawler = $client->request('GET', '/en/admin/photo/'.$property.'/edit');
        $this->assertSelectorTextContains('html', 'Upload photos');

        $photo = __DIR__.'/../../../public/images/bg.jpg';

        $form = $crawler->filter('.js-photo-dropzone')->form();
        $form['file']->upload($photo);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testAdminEditProperty(): void
    {
        $client = static::createClient([], self::SERVER);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'test']);

        $neighborhood = $client->getContainer()->get('doctrine')
            ->getRepository(Neighborhood::class)->findOneBy(['slug' => 'south-beach'])->getId();

        $metroStation = $client->getContainer()->get('doctrine')
            ->getRepository(Metro::class)->findOneBy(['slug' => 'government-center'])->getId();

        $feature = $client->getContainer()->get('doctrine')
            ->getRepository(Feature::class)->findOneBy(['name' => 'Secure parking']);

        $crawler = $client->request('GET', '/en/admin/property/'.$property->getId().'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'property[property_description][meta_title]' => 'Custom Meta Title',
            'property[neighborhood]' => $neighborhood,
            'property[metro_station]' => $metroStation,
            'property[features]' => [$feature->getId()],
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', sprintf(
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
        $client = static::createClient([], self::SERVER);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'test'])->getId();

        $crawler = $client->request('GET', '/en/admin/photo/'.$property.'/edit');

        $form = $crawler->selectButton('Delete')->form();
        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /*
     * This test changes the database contents by deleting a test Property.
     */
    public function testAdminDeleteProperty(): void
    {
        $client = static::createClient([], self::SERVER);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'test'])->getId();

        $crawler = $client->request('GET', '/en/admin/property?sort_by=id');
        $client->submit($crawler->filter('#delete-form-'.$property)->form());

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Property::class)->findOneBy([
                'slug' => 'test',
            ]));
    }
}
