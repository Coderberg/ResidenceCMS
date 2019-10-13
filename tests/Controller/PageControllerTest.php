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
                'add_contact_form' => 0,
            ]);

        $client->request('GET', sprintf('/info/%s', $page->getSlug()));
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testContactPage()
    {
        $client = static::createClient();
        $page = $client->getContainer()
            ->get('doctrine')
            ->getRepository(Page::class)
            ->findOneBy([
                'add_contact_form' => 1,
            ]);

        $crawler = $client->request('GET', sprintf('/info/%s', $page->getSlug()));
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form([
            'contact[from_name]' => 'Tester',
            'contact[from_email]' => 'test@test.com',
            'contact[message]' => 'Let\'s test it!',
        ]);
        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
}
