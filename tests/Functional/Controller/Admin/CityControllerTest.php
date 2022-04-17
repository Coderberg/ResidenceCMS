<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\City;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CityControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const NAME = 'Test';
    private const SLUG = 'test';
    private const EDITED_NAME = 'Edited';

    /**
     * This test changes the database contents by creating a new City.
     */
    public function testAdminNewCity(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request('GET', '/en/admin/locations/city/new');

        $form = $crawler->selectButton('Create city')->form([
            'city[name]' => self::NAME,
            'city[slug]' => self::SLUG,
            'city[title]' => self::NAME,
            'city[meta_title]' => 'Custom Meta Title',
            'city[meta_description]' => 'Custom Meta Description',
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $city = $this->getRepository($client, City::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($city);
        $this->assertSame(self::NAME, $city->getName());
        $this->assertSame(self::SLUG, $city->getSlug());
        $this->assertSame('Custom Meta Title', $city->getMetaTitle());
        $this->assertSame('Custom Meta Description', $city->getMetaDescription());
    }

    /**
     * This test changes the database contents by editing a city.
     */
    public function testAdminEditCity(): void
    {
        $client = $this->authAsAdmin($this);

        $city = $this->getRepository($client, City::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/locations/city/'.$city.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'city[name]' => self::EDITED_NAME,
            'city[title]' => 'Edited Title',
            'city[meta_title]' => '',
            'city[meta_description]' => 'Edited Meta Description',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedCity = $this->getRepository($client, City::class)
            ->findOneBy([
                'id' => $city,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedCity->getName());
        $this->assertSame('Edited Title', $editedCity->getTitle());
        $this->assertSame('Edited Meta Description', $editedCity->getMetaDescription());
    }

    /**
     * This test changes the database contents by deleting a test city.
     */
    public function testAdminDeleteCity(): void
    {
        $client = $this->authAsAdmin($this);

        $city = $this->getRepository($client, City::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/locations/city');
        $client->submit($crawler->filter('#delete-form-'.$city)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($this->getRepository($client, City::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
