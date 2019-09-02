<?php

declare(strict_types=1);

namespace App\Tests\Area\Admin;

use App\Entity\Area;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class AreaControllerTest extends WebTestCase
{
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    private const NAME = 'Test';
    private const SLUG = 'test';
    private const EDITED_NAME = 'Edited';

    /**
     * This test changes the database contents by creating a new Area.
     */
    public function testAdminNewArea()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);
        $crawler = $client->request('GET', '/admin/area/new');

        $form = $crawler->selectButton('Create area')->form([
            'area[name]' => self::NAME,
            'area[slug]' => self::SLUG,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $area = $client->getContainer()->get('doctrine')
            ->getRepository(Area::class)->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($area);
        $this->assertSame(self::NAME, $area->getName());
        $this->assertSame(self::SLUG, $area->getSlug());
    }

    /**
     * This test changes the database contents by editing a Area.
     */
    public function testAdminEditArea()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $area = $client->getContainer()->get('doctrine')
            ->getRepository(Area::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/area/'.$area.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'area[name]' => self::EDITED_NAME,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedArea = $client->getContainer()->get('doctrine')
            ->getRepository(Area::class)->findOneBy([
                'id' => $area,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedArea->getName());
    }

    /**
     * This test changes the database contents by deleting a test Area.
     */
    public function testAdminDeleteArea()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $area = $client->getContainer()->get('doctrine')
            ->getRepository(Area::class)->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/locality');
        $client->submit($crawler->filter('#delete-area-'.$area)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Area::class)->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
