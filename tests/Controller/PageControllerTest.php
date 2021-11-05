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

        $client->request('GET', sprintf('/en/info/%s', $page->getSlug()));
        $this->assertResponseIsSuccessful();
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

        $crawler = $client->request('GET', sprintf('/en/info/%s', $page->getSlug()));
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Submit')->form([
            'feedback[from_name]' => 'Tester',
            'feedback[from_email]' => 'test@test.com',
            'feedback[message]' => 'Let\'s test it!',
        ]);
        $client->submit($form);
        $this->assertEmailCount(1);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
}
