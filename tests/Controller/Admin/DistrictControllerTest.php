<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\District;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class DistrictControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    private const NAME = 'Test';
    private const SLUG = 'test';
    private const EDITED_NAME = 'Edited';

    /**
     * This test changes the database contents by creating a new District.
     */
    public function testAdminNewDistrict()
    {
        $client = static::createClient([], self::SERVER);
        $crawler = $client->request('GET', '/admin/locations/district/new');

        $form = $crawler->selectButton('Create district')->form([
            'district[name]' => self::NAME,
            'district[slug]' => self::SLUG,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $district = $client->getContainer()->get('doctrine')
            ->getRepository(District::class)->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($district);
        $this->assertSame(self::NAME, $district->getName());
        $this->assertSame(self::SLUG, $district->getSlug());
    }

    /**
     * This test changes the database contents by editing an District.
     */
    public function testAdminEditDistrict()
    {
        $client = static::createClient([], self::SERVER);

        $district = $client->getContainer()->get('doctrine')
            ->getRepository(District::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/locations/district/'.$district.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'district[name]' => self::EDITED_NAME,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedDistrict = $client->getContainer()->get('doctrine')
            ->getRepository(District::class)->findOneBy([
                'id' => $district,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedDistrict->getName());
    }

    /**
     * This test changes the database contents by deleting a test District.
     */
    public function testAdminDeleteDistrict()
    {
        $client = static::createClient([], self::SERVER);

        $crawler = $client->request('GET', '/admin/locations/district');

        $district = $client->getContainer()->get('doctrine')
            ->getRepository(District::class)->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $client->submit($crawler->filter('#delete-district-'.$district)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(District::class)->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
