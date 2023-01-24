<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Ajax;

use App\Entity\City;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CityControllerTest extends WebTestCase
{
    use WebTestHelper;

    public function testSomething(): void
    {
        $client = $this->authAsUser($this);
        $crawler = $client->request('GET', '/en/user/property/new');

        // Find CSRF token
        $token = $crawler->filter('#property_form')->first()->attr('data-token');

        // Find city ID
        $city = $this->getRepository($client, City::class)
            ->findOneBy(['slug' => 'miami'])->getId();

        // Request without CSRF token
        $client->request('GET', sprintf('/en/city/%s.json', $city));
        $this->assertResponseStatusCodeSame(419);

        // Request with wrong city ID
        $client->request('GET', sprintf('/en/city/%s.json?csrf_token=%s', 999, $token));
        $this->assertResponseStatusCodeSame(404);

        // Request with CSRF token
        $client->request('GET', sprintf('/en/city/%s.json?csrf_token=%s', $city, $token));
        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();
        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

        $this->assertContainsWords($response, ['Miami', 'South Beach', 'Allapattah']);
    }

    private function assertContainsWords(Response $response, array $words): void
    {
        foreach ($words as $word) {
            $this->assertStringContainsString($word, (string) $response->getContent());
        }
    }
}
