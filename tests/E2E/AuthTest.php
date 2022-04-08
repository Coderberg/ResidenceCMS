<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

final class AuthTest extends PantherTestCase
{
    public function testLoginAsUserWithWrongPassword(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/en/login');
        $this->assertSelectorTextContains('.card-header', 'Secure Sign in');

        $form = $crawler->selectButton('Sign in')->form([
            'login_form[username]' => 'user',
            'login_form[password]' => 'user_admin',
        ]);
        $client->submit($form);
        $client->waitFor('.alert-danger');
        $this->assertSelectorTextContains('.alert-danger', 'Invalid credentials');
    }

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

        // Log Out
        $client->request('GET', '/en/logout');
        $this->assertSelectorTextContains('.h3', 'Popular Listing');
    }

    public function testLoginAsAdmin(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/en/login');
        $this->assertSelectorTextContains('.card-header', 'Secure Sign in');

        $form = $crawler->selectButton('Sign in')->form([
            'login_form[username]' => 'admin',
            'login_form[password]' => 'admin',
        ]);
        $client->submit($form);
        $client->waitFor('.fa-users-cog');
        $this->assertSelectorTextContains('#userDropdown', 'Admin');

        // Log Out
        $client->request('GET', '/en/logout');
        $this->assertSelectorTextContains('.h3', 'Popular Listing');
    }
}
