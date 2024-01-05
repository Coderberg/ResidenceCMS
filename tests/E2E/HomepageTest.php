<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

final class HomepageTest extends PantherTestCase
{
    public function testHomepage(): void
    {
        $client = self::createPantherClient();

        // Page 1
        $crawler = $client->request('GET', '/');
        $this->assertSelectorTextContains('h1', 'Popular Listing');
        $this->assertPageTitleSame('Site Title');
        $this->assertCount(6, $crawler->filter('.card-title'));

        // Page 2
        $crawler = $client->request('GET', '/en/?page=2');
        $this->assertPageTitleSame('Site Title - Page 2');
        $this->assertCount(1, $crawler->filter('.card-title'));
    }
}
