<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SitemapControllerTest extends WebTestCase
{
    private const CONTENT_TYPE = 'text/xml; charset=UTF-8';

    public function testSitemap(): void
    {
        $client = self::createClient();
        $client->request('GET', '/en/sitemap.xml');
        $this->assertResponseHeaderSame('Content-Type', self::CONTENT_TYPE);
    }

    public function testCitiesSitemap(): void
    {
        $client = self::createClient();
        $client->request('GET', '/en/sitemap/cities.xml');
        $this->assertResponseHeaderSame('Content-Type', self::CONTENT_TYPE);
    }

    public function testPropertiesSitemap(): void
    {
        $client = self::createClient();
        $client->request('GET', '/en/sitemap/properties.xml');
        $this->assertResponseHeaderSame('Content-Type', self::CONTENT_TYPE);
    }
}
