<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DashboardControllerTest extends WebTestCase
{
    use WebTestHelper;

    public function testAdminDashboard(): void
    {
        $client = $this->authAsAdmin($this);
        $client->request('GET', '/en/admin');
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', '/admin'));
    }
}
