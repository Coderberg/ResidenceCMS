<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\City;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CityControllerTest extends AbstractLocationControllerTest
{
    /**
     * This test changes the database contents by creating a new City.
     */
    public function testAdminNewCity(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/city/new');

        $form = $crawler->selectButton('Create city')->form([
            'city[name]' => self::NAME,
            'city[slug]' => self::SLUG,
            'city[title]' => self::NAME,
            'city[meta_title]' => 'Custom Meta Title',
            'city[meta_description]' => 'Custom Meta Description',
        ]);
        $this->client->submit($form);

        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $city = $this->getRepository($this->client, City::class)
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
        $city = $this->getRepository($this->client, City::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/city/'.$city.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'city[name]' => self::EDITED_NAME,
            'city[title]' => 'Edited Title',
            'city[meta_title]' => '',
            'city[meta_description]' => 'Edited Meta Description',
        ]);

        $this->client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $editedCity = $this->getRepository($this->client, City::class)
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
        $city = $this->getRepository($this->client, City::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/city');
        $this->client->submit($crawler->filter('#delete-form-'.$city)->form());
        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $this->assertNull($this->getRepository($this->client, City::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
