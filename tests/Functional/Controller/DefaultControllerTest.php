<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider getPublicUrls
     */
    public function testPublicUrls(string $url): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, $url);
        $this->assertResponseIsSuccessful(\sprintf('The %s public URL loads correctly.', $url));
    }

    /**
     * @dataProvider getSecureUrls
     */
    public function testSecureUrls(string $url): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode(), $response->getContent());
        $this->assertSame(
            'http://localhost/en/login',
            $response->getTargetUrl(),
            \sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }

    /**
     * @dataProvider getMenuItems
     */
    public function testMenuItems(string $url): void
    {
        $client = self::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/');
        $link = $crawler->filter(\sprintf('a[href="%s"]', $url))->link();
        $urlFound = $link->getUri();
        if (false === mb_strpos($url, 'https://')) {
            $this->assertSame('http://localhost'.$url, $urlFound);
        } else {
            $this->assertSame($url, $urlFound);
        }
    }

    public function test404(): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, '/en/wrong-url');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function getPublicUrls(): \Generator
    {
        yield ['/en/'];
        yield ['/en/?page=2'];
        yield ['/en/login'];
        yield ['/en/map'];
        yield ['/en/info/contact'];
        yield ['/en/city/palm-beach'];
        yield ['/en/?city=0&deal_type=0&category=0'];
        yield ['/en/?city=0&deal_type=0&category=0&page=2'];
    }

    public function getSecureUrls(): \Generator
    {
        yield ['/en/admin'];
        yield ['/en/admin/property'];
        yield ['/en/admin/property/new'];
        yield ['/en/admin/property/1/edit'];
        yield ['/en/admin/settings'];
        yield ['/en/user/property'];
    }

    public function getMenuItems(): \Generator
    {
        yield ['/en/'];
        yield ['/en/info/about-us'];
        yield ['/en/info/contact'];
        yield ['https://github.com/Coderberg/ResidenceCMS'];
    }
}
