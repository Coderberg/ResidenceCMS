<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\City;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CityControllerTest extends WebTestCase
{
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    private const NAME = 'Test';
    private const SLUG = 'test';
    private const EDITED_NAME = 'Edited';

    /**
     * This test changes the database contents by creating a new City.
     */
    public function testAdminNewCity()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $crawler = $client->request('GET', '/admin/city/new');

        $form = $crawler->selectButton('Create city')->form([
            'city[name]' => self::NAME,
            'city[slug]' => self::SLUG,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $city = $client->getContainer()->get('doctrine')
            ->getRepository(City::class)->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($city);
        $this->assertSame(self::NAME, $city->getName());
        $this->assertSame(self::SLUG, $city->getSlug());
    }

    /**
     * This test changes the database contents by editing a city.
     */
    public function testAdminEditCity()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $city = $client->getContainer()
            ->get('doctrine')->getRepository(City::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/city/'.$city.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'city[name]' => self::EDITED_NAME,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedCity = $client->getContainer()->get('doctrine')
            ->getRepository(City::class)->findOneBy([
                'id' => $city,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedCity->getName());
    }

    /**
     * This test changes the database contents by deleting a test city.
     */
    public function testAdminDeleteCity()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $city = $client->getContainer()->get('doctrine')
            ->getRepository(City::class)->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/city');
        $client->submit($crawler->filter('#delete-form-'.$city)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(City::class)->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
