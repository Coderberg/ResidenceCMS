<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Feature;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FeatureControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const FEATURE = 'New feature';
    private const EDITED = 'Edited feature';

    /**
     * This test changes the database contents by creating a new Feature.
     */
    public function testAdminNewFeature(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/feature/new');

        $form = $crawler->selectButton('Create feature')->form([
            'feature[name]' => self::FEATURE,
        ]);
        $client->submit($form);

        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
        $feature = $this->getRepository($client, Feature::class)->findOneBy([
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
        $client = $this->authAsAdmin($this);

        $feature = $this->getRepository($client, Feature::class)
            ->findOneBy([
                'name' => self::FEATURE,
            ])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/feature/'.$feature.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'feature[name]' => self::EDITED,
        ]);

        $client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $editedFeature = $this->getRepository($client, Feature::class)->findOneBy([
            'id' => $feature,
        ]);

        $this->assertSame(self::EDITED, $editedFeature->getName());
    }

    /**
     * This test changes the database contents by deleting a test Feature.
     */
    public function testAdminDeleteFeature(): void
    {
        $client = $this->authAsAdmin($this);

        $feature = $this->getRepository($client, Feature::class)->findOneBy([
            'name' => self::EDITED,
        ])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/feature');
        $client->submit($crawler->filter('#delete-form-'.$feature)->form());
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $this->assertNull($this->getRepository($client, Feature::class)->findOneBy([
            'name' => self::EDITED,
        ]));
    }
}
