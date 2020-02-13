<?php

declare(strict_types=1);

namespace App\Tests\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AccountControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'user',
        ]);

        $crawler = $client->request('GET', '/user/account');
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', '/user/account'));
        $this->assertCount(2, $crawler->filter('.property-box-img'));
        $this->assertSelectorTextContains('html', 'My properties (2)');

        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(403);
    }
}
