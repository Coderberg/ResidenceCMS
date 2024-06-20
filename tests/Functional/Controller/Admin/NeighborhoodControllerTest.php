<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Neighborhood;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class NeighborhoodControllerTest extends AbstractLocationControllerTest
{
    /**
     * This test changes the database contents by creating a new Neighborhood.
     */
    public function testAdminNewNeighborhood(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/neighborhood/new');

        $form = $crawler->selectButton('Create neighborhood')->form([
            'neighborhood[name]' => self::NAME,
            'neighborhood[slug]' => self::SLUG,
        ]);
        $this->client->submit($form);

        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $neighborhood = $this->getRepository($this->client, Neighborhood::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($neighborhood);
        $this->assertSame(self::NAME, $neighborhood->getName());
        $this->assertSame(self::SLUG, $neighborhood->getSlug());
    }

    /**
     * This test changes the database contents by editing an Neighborhood.
     */
    public function testAdminEditNeighborhood(): void
    {
        $neighborhood = $this->getRepository($this->client, Neighborhood::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/neighborhood/'.$neighborhood.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'neighborhood[name]' => self::EDITED_NAME,
        ]);

        $this->client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $editedNeighborhood = $this->getRepository($this->client, Neighborhood::class)
            ->findOneBy([
                'id' => $neighborhood,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedNeighborhood->getName());
    }

    /**
     * This test changes the database contents by deleting a test Neighborhood.
     */
    public function testAdminDeleteNeighborhood(): void
    {
        $neighborhood = $this->getRepository($this->client, Neighborhood::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/neighborhood');
        $this->client->submit($crawler->filter('#delete-neighborhood-'.$neighborhood)->form());
        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $this->assertNull($this->getRepository($this->client, Neighborhood::class)->findOneBy([
            'slug' => self::SLUG,
        ]));
    }
}
