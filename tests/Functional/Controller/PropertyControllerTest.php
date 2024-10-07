<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\City;
use App\Entity\Property;
use App\Entity\Settings;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class PropertyControllerTest extends WebTestCase
{
    use WebTestHelper;

    public function testIndex(): void
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request(Request::METHOD_GET, '/en/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertStringContainsString('Popular Listing', $crawler->filter('h1')
            ->text());
    }

    public function testProperty(): void
    {
        $client = self::createClient();
        $property = $this->getRepository($client, Property::class)
            ->findOneBy([
                'state' => 'published',
            ]);

        $crawler = $client->request(Request::METHOD_GET, \sprintf(
            '/en/%s/%s/%d',
            $property->getCity()->getSlug(),
            $property->getSlug(),
            $property->getId())
        );
        $this->assertResponseIsSuccessful();

        // Find link to City's page
        $link = $crawler->filter('.overview a')->link();
        $uri = $link->getUri();
        $client->request(Request::METHOD_GET, $uri);
        $this->assertResponseIsSuccessful();
    }

    public function testSearch(): void
    {
        $client = self::createClient();
        $repository = $this->getRepository($client, City::class);

        $city = $repository->findOneBy(['slug' => 'miami'])->getId();

        $crawler = $client->request(Request::METHOD_GET, \sprintf('/en/?city=%d&bedrooms=0', $city));
        $this->assertCount(3, $crawler->filter('.property-box-img'));

        $crawler = $client->request(Request::METHOD_GET, \sprintf('/en/?city=%d&bedrooms=1', $city));
        $this->assertCount(1, $crawler->filter('.property-box-img'));

        $crawler = $client->request(Request::METHOD_GET, \sprintf('/en/?city=%d&bedrooms=3', $city));
        $this->assertCount(0, $crawler->filter('.property-box-img'));

        $crawler = $client->request(Request::METHOD_GET, '/en/?guests=6');
        $this->assertCount(1, $crawler->filter('.property-box-img'));

        $crawler = $client->request(Request::METHOD_GET, '/en/?guests=3');
        $this->assertCount(4, $crawler->filter('.property-box-img'));
    }

    public function testSearchFilter(): void
    {
        $client = self::createClient();
        $repository = $this->getRepository($client, Settings::class);

        // Expects 3 fields in the filter on Homepage
        $crawler = $client->request(Request::METHOD_GET, '/en/');
        $this->assertCount(3, $crawler->filter('.form-control'));
        $this->assertSelectorTextNotContains('.search_form', 'Feature');

        // Disable 1 field
        $repository->updateSetting('show_filter_by_city', '0');

        // Expects 2 fields in the filter on Homepage
        $crawler = $client->request(Request::METHOD_GET, '/en/');
        $this->assertCount(2, $crawler->filter('.form-control'));
        $this->assertSelectorTextNotContains('.search_form', 'City');

        // Enable 2 fields
        $repository->updateSetting('show_filter_by_city', '1');
        $repository->updateSetting('show_filter_by_bedrooms', '1');

        // Expects 4 fields in the filter on Homepage
        $crawler = $client->request(Request::METHOD_GET, '/en/');
        $this->assertCount(4, $crawler->filter('.form-control'));
        $this->assertSelectorTextContains('.search_form', 'City');
        $this->assertSelectorTextContains('.search_form', 'Bedrooms');

        // Disable 1 field
        $repository->updateSetting('show_filter_by_bedrooms', '0');

        // Expects 3 fields in the filter on Homepage
        $crawler = $client->request(Request::METHOD_GET, '/en/');
        $this->assertCount(3, $crawler->filter('.form-control'));
        $this->assertSelectorTextNotContains('.search_form', 'Bedrooms');

        // Enable filter by features
        $repository->updateSetting('show_filter_by_features', '1');

        // Expects 4 fields in the filter on Homepage
        $crawler = $client->request(Request::METHOD_GET, '/');
        $this->assertCount(4, $crawler->filter('.form-control'));
        $this->assertSelectorTextContains('.search_form', 'Feature');

        // Disable filter by features
        $repository->updateSetting('show_filter_by_features', '0');

        // Expects 3 fields in the filter on Homepage
        $crawler = $client->request(Request::METHOD_GET, '/');
        $this->assertCount(3, $crawler->filter('.form-control'));
    }
}
