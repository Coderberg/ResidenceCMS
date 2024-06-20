<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\District;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DistrictControllerTest extends AbstractLocationControllerTest
{
    /**
     * This test changes the database contents by creating a new District.
     */
    public function testAdminNewDistrict(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/district/new');

        $form = $crawler->selectButton('Create district')->form([
            'district[name]' => self::NAME,
            'district[slug]' => self::SLUG,
        ]);
        $this->client->submit($form);

        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $district = $this->getRepository($this->client, District::class)->findOneBy([
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
        $district = $this->getRepository($this->client, District::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/district/'.$district.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'district[name]' => self::EDITED_NAME,
        ]);

        $this->client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $editedDistrict = $this->getRepository($this->client, District::class)
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
        $crawler = $this->client->request(Request::METHOD_GET, '/en/admin/locations/district');

        $district = $this->getRepository($this->client, District::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $this->client->submit($crawler->filter('#delete-district-'.$district)->form());
        $this->assertSame(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $this->assertNull($this->getRepository($this->client, District::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
