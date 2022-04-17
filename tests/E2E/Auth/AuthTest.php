<?php

declare(strict_types=1);

namespace App\Tests\E2E\Auth;

use App\Tests\Helper\PantherTestHelper;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Symfony\Component\Panther\PantherTestCase;

final class AuthTest extends PantherTestCase
{
    use PantherTestHelper;

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function testLoginAsUserWithWrongPassword(): void
    {
        $client = self::createPantherClient();
        $this->login($client, 'user', 'wrong');
        $client->waitFor('.alert-danger');
        $this->assertSelectorTextContains('.alert-danger', 'Invalid credentials');
    }

    public function testLoginAsUser(): void
    {
        $client = self::createPantherClient();
        $crawler = $this->login($client, 'user', 'user');

        $this->assertSelectorTextContains('h3', 'My properties');
        $this->assertCount(2, $crawler->filter('.card-title'));

        // Log Out
        $this->logout($client);
        $this->assertSelectorTextContains('.h3', 'Popular Listing');
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function testLoginAsAdmin(): void
    {
        $client = self::createPantherClient();
        $this->login($client, 'admin', 'admin');

        $client->waitFor('.fa-users-cog');
        $this->assertSelectorTextContains('#userDropdown', 'Admin');

        // Log Out
        $this->logout($client);
        $this->assertSelectorTextContains('.h3', 'Popular Listing');
    }
}
