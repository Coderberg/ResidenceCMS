<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\DealType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class DealTypeControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    private const NAME = 'Test';
    private const SLUG = 'test';
    private const EDITED_NAME = 'Edited';

    /**
     * This test changes the database contents by creating a new Deal Type.
     */
    public function testAdminNewDealType(): void
    {
        $client = self::createClient([], self::SERVER);

        $crawler = $client->request('GET', '/en/admin/deal_type/new');

        $form = $crawler->selectButton('Create deal type')->form([
            'deal_type[name]' => self::NAME,
            'deal_type[slug]' => self::SLUG,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $dealType = $client->getContainer()->get('doctrine')
            ->getRepository(DealType::class)->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($dealType);
        $this->assertSame(self::NAME, $dealType->getName());
        $this->assertSame(self::SLUG, $dealType->getSlug());
    }

    /**
     * This test changes the database contents by editing a Deal Type.
     */
    public function testAdminEditDealType(): void
    {
        $client = self::createClient([], self::SERVER);

        $dealType = $client->getContainer()->get('doctrine')
            ->getRepository(DealType::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/deal_type/'.$dealType.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'deal_type[name]' => self::EDITED_NAME,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedDealType = $client->getContainer()->get('doctrine')
            ->getRepository(DealType::class)->findOneBy([
                'id' => $dealType,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedDealType->getName());
    }

    /**
     * This test changes the database contents by deleting a test Deal Type.
     */
    public function testAdminDeleteDealType(): void
    {
        $client = self::createClient([], self::SERVER);

        $dealType = $client->getContainer()->get('doctrine')
            ->getRepository(DealType::class)->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/deal_type');
        $client->submit($crawler->filter('#delete-form-'.$dealType)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(DealType::class)->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
