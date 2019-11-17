<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\City;
use App\Entity\Property;
use App\Entity\Settings;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class PropertyControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('Latest properties', $crawler->filter('h3')
            ->text());
    }

    public function testProperty()
    {
        $client = static::createClient();
        // the service container is always available via the test client
        $property = $client->getContainer()
            ->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy([
                'published' => 1,
            ]);

        $client->request('GET', sprintf(
            '/%s/%s/%d',
            $property->getCity()->getSlug(),
            $property->getSlug(),
            $property->getId())
        );
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testSearch()
    {
        $client = static::createClient();
        $repository = $client->getContainer()->get('doctrine')
            ->getRepository(City::class);

        $city = $repository->findOneBy(['slug' => 'miami'])->getId();

        $crawler = $client->request('GET', sprintf('/search?city=%d&bedrooms=0', $city));
        $this->assertCount(3, $crawler->filter('.property-box-img'));

        $crawler = $client->request('GET', sprintf('/search?city=%d&bedrooms=1', $city));
        $this->assertCount(1, $crawler->filter('.property-box-img'));

        $crawler = $client->request('GET', sprintf('/search?city=%d&bedrooms=3', $city));
        $this->assertCount(0, $crawler->filter('.property-box-img'));
    }

    public function testSearchFilter()
    {
        $client = static::createClient();
        $repository = $client->getContainer()->get('doctrine')
            ->getRepository(Settings::class);

        // Expects 3 fields in the filter on Homepage
        $crawler = $client->request('GET', '/');
        $this->assertCount(3, $crawler->filter('.form-control'));

        // Disable 1 field
        $repository->updateSetting('show_filter_by_city', '0');

        // Expects 2 fields in the filter on Homepage
        $crawler = $client->request('GET', '/');
        $this->assertCount(2, $crawler->filter('.form-control'));

        // Enable 2 fields
        $repository->updateSetting('show_filter_by_city', '1');
        $repository->updateSetting('show_filter_by_bedrooms', '1');

        // Expects 4 fields in the filter on Homepage
        $crawler = $client->request('GET', '/');
        $this->assertCount(4, $crawler->filter('.form-control'));

        // Disable 1 field
        $repository->updateSetting('show_filter_by_bedrooms', '0');

        // Expects 3 fields in the filter on Homepage
        $crawler = $client->request('GET', '/');
        $this->assertCount(3, $crawler->filter('.form-control'));
    }
}
