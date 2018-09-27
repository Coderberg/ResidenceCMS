<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Locality;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class LocalityControllerTest extends WebTestCase
{
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    private const NAME = 'Test';
    private const SLUG = 'test';
    private const EDITED_NAME = 'Edited';

    /**
     * This test changes the database contents by creating a new locality
     */
    public function testAdminNewLocality()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW
        ]);

        $crawler = $client->request('GET', '/admin/locality/new');

        $form = $crawler->selectButton('Create locality')->form([
            'locality[name]' => self::NAME,
            'locality[slug]' => self::SLUG,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $locality = $client->getContainer()->get('doctrine')
            ->getRepository(Locality::class)->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($locality);
        $this->assertSame(self::NAME, $locality->getName());
        $this->assertSame(self::SLUG, $locality->getSlug());
    }

    /**
     * This test changes the database contents by editing a locality.
     */
    public function testAdminEditLocality()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW
        ]);

        $locality = $client->getContainer()
            ->get('doctrine')->getRepository(Locality::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/locality/' . $locality . '/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'locality[name]' => self::EDITED_NAME,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedLocality = $client->getContainer()->get('doctrine')
            ->getRepository(Locality::class)->findOneBy([
                'id' => $locality
            ]);

        $this->assertSame(self::EDITED_NAME, $editedLocality->getName());
    }

    /**
     * This test changes the database contents by deleting a test locality.
     */
    public function testAdminDeleteLocality()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW
        ]);

        $locality = $client->getContainer()->get('doctrine')
            ->getRepository(Locality::class)->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/locality');
        $client->submit($crawler->filter('#delete-form-' . $locality)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Locality::class)->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
