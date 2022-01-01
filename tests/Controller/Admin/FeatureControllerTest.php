<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Feature;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class FeatureControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    private const FEATURE = 'New feature';
    private const EDITED = 'Edited feature';

    /**
     * This test changes the database contents by creating a new Feature.
     */
    public function testAdminNewFeature(): void
    {
        $client = static::createClient([], self::SERVER);

        $crawler = $client->request('GET', '/en/admin/feature/new');

        $form = $crawler->selectButton('Create feature')->form([
            'feature[name]' => self::FEATURE,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $feature = $client->getContainer()->get('doctrine')
            ->getRepository(Feature::class)->findOneBy([
                'name' => self::FEATURE,
            ]);

        $this->assertNotNull($feature);
        $this->assertSame(self::FEATURE, $feature->getName());
    }

    /**
     * This test changes the database contents by editing a Feature.
     */
    public function testAdminEditFeature(): void
    {
        $client = static::createClient([], self::SERVER);

        $feature = $client->getContainer()->get('doctrine')
            ->getRepository(Feature::class)
            ->findOneBy([
                'name' => self::FEATURE,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/feature/'.$feature.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'feature[name]' => self::EDITED,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedFeature = $client->getContainer()->get('doctrine')
            ->getRepository(Feature::class)->findOneBy([
                'id' => $feature,
            ]);

        $this->assertSame(self::EDITED, $editedFeature->getName());
    }

    /**
     * This test changes the database contents by deleting a test Feature.
     */
    public function testAdminDeleteFeature(): void
    {
        $client = static::createClient([], self::SERVER);

        $feature = $client->getContainer()->get('doctrine')
            ->getRepository(Feature::class)->findOneBy([
                'name' => self::EDITED,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/feature');
        $client->submit($crawler->filter('#delete-form-'.$feature)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Feature::class)->findOneBy([
                'name' => self::EDITED,
            ]));
    }
}
