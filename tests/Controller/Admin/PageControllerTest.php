<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class PageControllerTest extends WebTestCase
{
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    private const TITLE = 'Test';
    private const SLUG = 'test';
    private const EDITED_TITLE = 'Edited';

    /**
     * This test changes the database contents by creating a new Page.
     */
    public function testAdminNewPage()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);
        $crawler = $client->request('GET', '/admin/page/new');

        $form = $crawler->selectButton('Create page')->form([
            'page[title]' => self::TITLE,
            'page[description]' => self::TITLE,
            'page[slug]' => self::SLUG,
            'page[content]' => self::TITLE,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $page = $client->getContainer()->get('doctrine')
            ->getRepository(Page::class)->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($page);
        $this->assertSame(self::TITLE, $page->getTitle());
        $this->assertSame(self::SLUG, $page->getSlug());
    }

    /**
     * This test changes the database contents by editing a Page.
     */
    public function testAdminEditPage()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $page = $client->getContainer()->get('doctrine')
            ->getRepository(Page::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/page/'.$page.'/edit');

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
    public function testAdminDeletePage()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $page = $client->getContainer()->get('doctrine')
            ->getRepository(Page::class)->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/page');
        $client->submit($crawler->filter('#delete-form-'.$page)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Page::class)->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
