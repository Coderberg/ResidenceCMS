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
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    /**
     * This test changes the database contents by creating a new Property.
     */
    public function testAdminNewProperty()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $crawler = $client->request('GET', '/admin/property/new');

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
            'property[title]' => 'test',
            'property[description]' => 'test',
            'property[address]' => 'test',
            'property[content]' => 'test',
        ]);

        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testAdminEditPhoto()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['title' => 'test'])->getId();

        $crawler = $client->request('GET', '/admin/photo/'.$property.'/edit');
        $this->assertSelectorTextContains('html', 'Upload photos');

        $photo = __DIR__.'/../../../public/images/bg.jpg';

        $form = $crawler->filter('.js-photo-dropzone')->form();
        $form['file']->upload($photo);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testAdminEditProperty()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['title' => 'test']);

        $neighborhood = $client->getContainer()->get('doctrine')
            ->getRepository(Neighborhood::class)->findOneBy(['slug' => 'south-beach'])->getId();

        $metroStation = $client->getContainer()->get('doctrine')
            ->getRepository(Metro::class)->findOneBy(['slug' => 'government-center'])->getId();

        $feature = $client->getContainer()->get('doctrine')
            ->getRepository(Feature::class)->findOneBy(['name' => 'High Impact Doors']);

        $crawler = $client->request('GET', '/admin/property/'.$property->getId().'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'property[neighborhood]' => $neighborhood,
            'property[metro_station]' => $metroStation,
            'property[features]' => [$feature->getId()],
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', sprintf(
                '/%s/%s/%d',
                $property->getCity()->getSlug(),
                $property->getSlug(),
                $property->getId())
        );

        $this->assertContains('High Impact Doors', $crawler->html());
        $this->assertContains('Government Center', $crawler->html());
        $this->assertContains('South Beach', $crawler->html());
    }

    public function testAdminDeletePhoto()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['title' => 'test'])->getId();

        $crawler = $client->request('GET', '/admin/photo/'.$property.'/edit');

        $form = $crawler->selectButton('Delete')->form();
        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /*
     * This test changes the database contents by deleting a test Property.
     */
    public function testAdminDeleteProperty()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['title' => 'test'])->getId();

        $crawler = $client->request('GET', '/admin/property');
        $client->submit($crawler->filter('#delete-form-'.$property)->form());

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Property::class)->findOneBy([
                'title' => 'test',
            ]));
    }
}
