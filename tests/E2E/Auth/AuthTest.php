<?php

declare(strict_types=1);

namespace App\Tests\E2E\Auth;

use App\Tests\E2E\AuthHelper;
use Symfony\Component\Panther\PantherTestCase;

final class AuthTest extends PantherTestCase
{
    use AuthHelper;

    public function testLoginAsUserWithWrongPassword(): void
    {
        $client = static::createPantherClient();
        $this->login($client, 'user', 'wrong');
        $client->waitFor('.alert-danger');
        $this->assertSelectorTextContains('.alert-danger', 'Invalid credentials');
    }

    public function testLoginAsUser(): void
    {
        $client = static::createPantherClient();
        $crawler = $this->login($client, 'user', 'user');

        $this->assertSelectorTextContains('h3', 'My properties');
        $this->assertCount(2, $crawler->filter('.card-title'));

        // Log Out
        $this->logout($client);
        $this->assertSelectorTextContains('.h3', 'Popular Listing');
    }

    public function testLoginAsAdmin(): void
    {
        $client = static::createPantherClient();
        $this->login($client, 'admin', 'admin');

        $client->waitFor('.fa-users-cog');
        $this->assertSelectorTextContains('#userDropdown', 'Admin');

        // Log Out
        $this->logout($client);
        $this->assertSelectorTextContains('.h3', 'Popular Listing');
    }
}
