<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\Page;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PageControllerTest extends WebTestCase
{
    use WebTestHelper;

    public function testPage(): void
    {
        $client = self::createClient();
        $page = $this->getRepository($client, Page::class)
            ->findOneBy([
                'add_contact_form' => 0,
            ]);

        $client->request(Request::METHOD_GET, \sprintf('/en/info/%s', $page->getSlug()));
        $this->assertResponseIsSuccessful();
    }

    public function testContactPage(): void
    {
        $client = self::createClient();
        $page = $this->getRepository($client, Page::class)
            ->findOneBy([
                'add_contact_form' => 1,
            ]);

        $crawler = $client->request(Request::METHOD_GET, \sprintf('/en/info/%s', $page->getSlug()));
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Submit')->form([
            'feedback[from_name]' => 'Tester',
            'feedback[from_email]' => 'test@test.com',
            'feedback[message]' => 'Let\'s test it!',
        ]);
        $client->submit($form);
        $this->assertEmailCount(1);
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }
}
