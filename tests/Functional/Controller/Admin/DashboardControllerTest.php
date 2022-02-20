<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DashboardControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    public function testAdminDashboard(): void
    {
        $client = self::createClient([], self::SERVER);
        $client->request('GET', '/en/admin');
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', '/admin'));
    }
}
