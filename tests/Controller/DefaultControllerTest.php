<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider getPublicUrls
     */
    public function testPublicUrls(string $url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', $url));
    }

    /**
     * @dataProvider getSecureUrls
     */
    public function testSecureUrls(string $url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertSame(
            'http://localhost/login',
            $response->getTargetUrl(),
            sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }

    public function test404()
    {
        $client = static::createClient();
        $client->request('GET', '/wrong-url');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function getPublicUrls()
    {
        yield ['/'];
        yield ['/?page=2'];
        yield ['/login'];
        yield ['/map'];
        yield ['/info/contact'];
        yield ['/city/palm-beach'];
        yield ['/?city=0&deal_type=0&category=0'];
        yield ['/?city=0&deal_type=0&category=0&page=2'];
    }

    public function getSecureUrls()
    {
        yield ['/admin'];
        yield ['/admin/property'];
        yield ['/admin/property/new'];
        yield ['/admin/property/1/edit'];
        yield ['/admin/settings'];
        yield ['/user/property'];
    }
}
