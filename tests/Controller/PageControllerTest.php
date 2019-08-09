<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class PageControllerTest extends WebTestCase
{
    public function testPage()
    {
        $client = static::createClient();
        // the service container is always available via the test client
        $page = $client->getContainer()
            ->get('doctrine')
            ->getRepository(Page::class)
            ->findOneBy([
                'show_in_menu' => 1,
            ]);

        $client->request('GET', sprintf('/info/%s', $page->getSlug()));
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
