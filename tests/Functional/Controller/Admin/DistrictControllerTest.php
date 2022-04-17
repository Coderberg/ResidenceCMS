<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\District;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class DistrictControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const NAME = 'Test';
    private const SLUG = 'test';
    private const EDITED_NAME = 'Edited';

    /**
     * This test changes the database contents by creating a new District.
     */
    public function testAdminNewDistrict(): void
    {
        $client = $this->authAsAdmin($this);
        $crawler = $client->request('GET', '/en/admin/locations/district/new');

        $form = $crawler->selectButton('Create district')->form([
            'district[name]' => self::NAME,
            'district[slug]' => self::SLUG,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $district = $this->getRepository($client, District::class)->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($district);
        $this->assertSame(self::NAME, $district->getName());
        $this->assertSame(self::SLUG, $district->getSlug());
    }

    /**
     * This test changes the database contents by editing an District.
     */
    public function testAdminEditDistrict(): void
    {
        $client = $this->authAsAdmin($this);

        $district = $this->getRepository($client, District::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/locations/district/'.$district.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'district[name]' => self::EDITED_NAME,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedDistrict = $this->getRepository($client, District::class)
            ->findOneBy([
                'id' => $district,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedDistrict->getName());
    }

    /**
     * This test changes the database contents by deleting a test District.
     */
    public function testAdminDeleteDistrict(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request('GET', '/en/admin/locations/district');

        $district = $this->getRepository($client, District::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $client->submit($crawler->filter('#delete-district-'.$district)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($this->getRepository($client, District::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
