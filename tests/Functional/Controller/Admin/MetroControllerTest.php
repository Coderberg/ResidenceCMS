<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Metro;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MetroControllerTest extends AbstractLocationControllerTest
{
    /**
     * This test changes the database contents by creating a new Metro station.
     */
    public function testAdminNewStation(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/metro/new');

        $form = $crawler->selectButton('Create metro station')->form([
            'metro[name]' => self::NAME,
            'metro[slug]' => self::SLUG,
        ]);
        $this->client->submit($form);

        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $station = $this->getRepository($this->client, Metro::class)->findOneBy([
            'slug' => self::SLUG,
        ]);

        $this->assertNotNull($station);
        $this->assertSame(self::NAME, $station->getName());
        $this->assertSame(self::SLUG, $station->getSlug());
    }

    /**
     * This test changes the database contents by editing a Metro station.
     */
    public function testAdminEditStation(): void
    {
        $station = $this->getRepository($this->client, Metro::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/metro/'.$station.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'metro[name]' => self::EDITED_NAME,
        ]);

        $this->client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());

        $editedStation = $this->getRepository($this->client, Metro::class)
            ->findOneBy([
                'id' => $station,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedStation->getName());
    }

    /**
     * This test changes the database contents by deleting a test Metro station.
     */
    public function testAdminDeleteStation(): void
    {
        $station = $this->getRepository($this->client, Metro::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/metro');
        $this->client->submit($crawler->filter('#delete-metro-'.$station)->form());
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());

        $this->assertNull($this->getRepository($this->client, Metro::class)->findOneBy([
            'slug' => self::SLUG,
        ]));
    }
}
