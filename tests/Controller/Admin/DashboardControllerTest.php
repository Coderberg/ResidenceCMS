<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DashboardControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    public function testAdminDashboard()
    {
        $client = static::createClient([], self::SERVER);
        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', '/admin'));
    }
}
