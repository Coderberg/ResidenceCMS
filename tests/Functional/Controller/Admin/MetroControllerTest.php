<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Metro;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class MetroControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    private const NAME = 'Test';
    private const SLUG = 'test';
    private const EDITED_NAME = 'Edited';

    /**
     * This test changes the database contents by creating a new Metro station.
     */
    public function testAdminNewStation(): void
    {
        $client = self::createClient([], self::SERVER);

        $crawler = $client->request('GET', '/en/admin/locations/metro/new');

        $form = $crawler->selectButton('Create metro station')->form([
            'metro[name]' => self::NAME,
            'metro[slug]' => self::SLUG,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $station = $client->getContainer()->get('doctrine')
            ->getRepository(Metro::class)->findOneBy([
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
        $client = self::createClient([], self::SERVER);

        $station = $client->getContainer()->get('doctrine')
            ->getRepository(Metro::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/locations/metro/'.$station.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'metro[name]' => self::EDITED_NAME,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedStation = $client->getContainer()->get('doctrine')
            ->getRepository(Metro::class)->findOneBy([
                'id' => $station,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedStation->getName());
    }

    /**
     * This test changes the database contents by deleting a test Metro station.
     */
    public function testAdminDeleteStation(): void
    {
        $client = self::createClient([], self::SERVER);

        $station = $client->getContainer()->get('doctrine')
            ->getRepository(Metro::class)->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/locations/metro');
        $client->submit($crawler->filter('#delete-metro-'.$station)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Metro::class)->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
