<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

final class AuthTest extends PantherTestCase
{
    public function testLoginAsUser(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/en/login');
        $this->assertSelectorTextContains('.card-header', 'Secure Sign in');

        $form = $crawler->selectButton('Sign in')->form([
            'login_form[username]' => 'user',
            'login_form[password]' => 'user',
        ]);
        $client->submit($form);
        $crawler = $client->waitFor('.js-counter');
        $this->assertSelectorTextContains('h3', 'My properties');
        $this->assertCount(2, $crawler->filter('.card-title'));
    }
}
