<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SitemapControllerTest extends WebTestCase
{
    public function testSitemap()
    {
        $client = static::createClient();
        $client->request('GET', '/en/sitemap.xml');
        $this->assertResponseHeaderSame('Content-Type', 'text/xml; charset=UTF-8');
    }

    public function testCitiesSitemap()
    {
        $client = static::createClient();
        $client->request('GET', '/en/sitemap/cities.xml');
        $this->assertResponseHeaderSame('Content-Type', 'text/xml; charset=UTF-8');
    }

    public function testPropertiesSitemap()
    {
        $client = static::createClient();
        $client->request('GET', '/en/sitemap/properties.xml');
        $this->assertResponseHeaderSame('Content-Type', 'text/xml; charset=UTF-8');
    }
}
