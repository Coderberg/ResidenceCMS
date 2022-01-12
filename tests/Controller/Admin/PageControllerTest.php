<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Menu;
use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class PageControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    private const TITLE = 'Test';
    private const SLUG = 'test';
    private const LOCALE = 'en';
    private const EDITED_TITLE = 'Edited';

    /**
     * This test changes the database contents by creating a new Page.
     */
    public function testAdminNewPage(): void
    {
        $client = self::createClient([], self::SERVER);
        $crawler = $client->request('GET', '/en/admin/page/new');

        $form = $crawler->selectButton('Create page')->form([
            'page[title]' => self::TITLE,
            'page[description]' => self::TITLE,
            'page[slug]' => self::SLUG,
            'page[locale]' => self::LOCALE,
            'page[content]' => self::TITLE,
            'page[show_in_menu]' => true,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $page = $client->getContainer()->get('doctrine')
            ->getRepository(Page::class)->findOneBy([
                'slug' => self::SLUG,
                'locale' => self::LOCALE,
            ]);

        $this->assertNotNull($page);
        $this->assertSame(self::TITLE, $page->getTitle());
        $this->assertSame(self::SLUG, $page->getSlug());

        $menu = $client->getContainer()->get('doctrine')
            ->getRepository(Menu::class)->findOneBy([
                'title' => self::TITLE,
            ]);

        $this->assertSame(self::TITLE, $menu->getTitle());
    }

    /**
     * This test changes the database contents by editing a Page.
     */
    public function testAdminEditPage(): void
    {
        $client = self::createClient([], self::SERVER);

        $page = $client->getContainer()->get('doctrine')
            ->getRepository(Page::class)
            ->findOneBy([
                'slug' => self::SLUG,
                'locale' => self::LOCALE,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/page/'.$page.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'page[title]' => self::EDITED_TITLE,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedPage = $client->getContainer()->get('doctrine')
            ->getRepository(Page::class)->findOneBy([
                'id' => $page,
            ]);

        $this->assertSame(self::EDITED_TITLE, $editedPage->getTitle());
    }

    /**
     * This test changes the database contents by deleting a test Page.
     */
    public function testAdminDeletePage(): void
    {
        $client = self::createClient([], self::SERVER);

        $page = $client->getContainer()->get('doctrine')
            ->getRepository(Page::class)->findOneBy([
                'slug' => self::SLUG,
                'locale' => self::LOCALE,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/page');
        $client->submit($crawler->filter('#delete-form-'.$page)->form());

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Page::class)->findOneBy([
                'slug' => self::SLUG,
                'locale' => self::LOCALE,
            ]));

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Menu::class)->findOneBy([
                'title' => self::TITLE,
            ]));
    }
}
