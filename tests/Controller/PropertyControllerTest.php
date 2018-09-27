<?php

namespace App\Tests\Controller;

use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PropertyControllerTest extends WebTestCase
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
                'published' => 1
            ]);

        $client->request('GET', sprintf('/property/%s', $property->getId()));
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
