<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class DashboardControllerTest extends WebTestCase
{
    use WebTestHelper;

    public function testAdminDashboard(): void
    {
        $client = $this->authAsAdmin($this);
        $client->request(Request::METHOD_GET, '/en/admin');
        $this->assertResponseIsSuccessful(\sprintf('The %s public URL loads correctly.', '/admin'));
    }
}
