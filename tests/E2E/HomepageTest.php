<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

final class HomepageTest extends PantherTestCase
{
    public function testHomepage(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Popular Listing');
    }
}
