<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class MenuControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    private const TITLE = 'Custom Menu Item';
    private const URL = '/?custom-link';
    private const EDITED_TITLE = 'Edited';

    /**
     * This test changes the database contents by creating a new Item.
     */
    public function testAdminNewItem(): void
    {
        $client = static::createClient([], self::SERVER);

        $crawler = $client->request('GET', '/admin/menu/new');

        $form = $crawler->selectButton('Save changes')->form([
            'menu[title]' => self::TITLE,
            'menu[url]' => self::URL,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $item = $client->getContainer()->get('doctrine')
            ->getRepository(Menu::class)->findOneBy([
                'url' => self::URL,
            ]);

        $this->assertNotNull($item);
        $this->assertSame(self::TITLE, $item->getTitle());
        $this->assertSame(self::URL, $item->getUrl());
    }

    /**
     * This test changes the database contents by editing a Menu Item.
     */
    public function testAdminEditItem()
    {
        $client = static::createClient([], self::SERVER);

        $item = $client->getContainer()->get('doctrine')
            ->getRepository(Menu::class)
            ->findOneBy([
                'url' => self::URL,
            ])->getId();

        $crawler = $client->request('GET', '/admin/menu/'.$item.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'menu[title]' => self::EDITED_TITLE,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedItem = $client->getContainer()->get('doctrine')
            ->getRepository(Menu::class)->findOneBy([
                'id' => $item,
            ]);

        $this->assertSame(self::EDITED_TITLE, $editedItem->getTitle());
    }

    /**
     * This test changes the database contents by deleting a test Item.
     */
    public function testAdminDeleteItem()
    {
        $client = static::createClient([], self::SERVER);

        $item = $client->getContainer()->get('doctrine')
            ->getRepository(Menu::class)->findOneBy([
                'url' => self::URL,
            ])->getId();

        $crawler = $client->request('GET', '/admin/menu');
        $client->submit($crawler->filter('#delete-form-'.$item)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Menu::class)->findOneBy([
                'url' => self::URL,
            ]));
    }
}
