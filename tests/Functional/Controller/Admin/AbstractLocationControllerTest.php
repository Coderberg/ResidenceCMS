<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractLocationControllerTest extends WebTestCase
{
    use WebTestHelper;

    protected const NAME = 'Test';
    protected const SLUG = 'test';
    protected const EDITED_NAME = 'Edited';

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->authAsAdmin($this);
    }
}
