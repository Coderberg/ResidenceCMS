<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client as PantherClient;

trait AuthHelper
{
    public function login(PantherClient $client, string $username, string $password): Crawler
    {
        $crawler = $client->request('GET', '/en/login');
        $form = $crawler->selectButton('Sign in')->form([
            'login_form[username]' => $username,
            'login_form[password]' => $password,
        ]);

        return $client->submit($form);
    }

    public function logout(PantherClient $client): void
    {
        $client->request('GET', '/en/logout');
    }

    public function authAsAdmin(WebTestCase $testCase)
    {
        return $testCase::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
    }
}
