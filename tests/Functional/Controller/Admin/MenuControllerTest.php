<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Menu;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MenuControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const TITLE = 'Custom Menu Item';
    private const URL = '/?custom-link';
    private const LOCALE = 'en';
    private const EDITED_TITLE = 'Edited';

    /**
     * This test changes the database contents by creating a new Item.
     */
    public function testAdminNewItem(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/menu/new');

        $form = $crawler->selectButton('Save changes')->form([
            'menu[title]' => self::TITLE,
            'menu[url]' => self::URL,
            'menu[locale]' => self::LOCALE,
        ]);
        $client->submit($form);

        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
        $item = $this->getRepository($client, Menu::class)
            ->findOneBy([
                'url' => self::URL,
                'locale' => self::LOCALE,
            ]);

        $this->assertNotNull($item);
        $this->assertSame(self::TITLE, $item->getTitle());
        $this->assertSame(self::URL, $item->getUrl());
    }

    /**
     * This test changes the database contents by editing a Menu Item.
     */
    public function testAdminEditItem(): void
    {
        $client = $this->authAsAdmin($this);

        $item = $this->getRepository($client, Menu::class)
            ->findOneBy([
                'url' => self::URL,
                'locale' => self::LOCALE,
            ])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/menu/'.$item.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'menu[title]' => self::EDITED_TITLE,
        ]);

        $client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $editedItem = $this->getRepository($client, Menu::class)
            ->findOneBy([
                'id' => $item,
            ]);

        $this->assertSame(self::EDITED_TITLE, $editedItem->getTitle());
    }

    /**
     * This test changes the database contents by sorting Menu Items.
     */
    public function testAdminSortItems(): void
    {
        $client = $this->authAsAdmin($this);
        $crawler = $client->request(Request::METHOD_GET, '/en/admin/menu');
        $token = $this->getCsrfToken($crawler);
        $items = $this->getRepository($client, Menu::class)
            ->findItems();

        $itemsArray = array_map(fn ($item) => $item->getId(), $items);

        $uri = '/en/admin/menu/sort';
        $client->request(Request::METHOD_POST, $uri, [
            'csrf-token' => $token,
            'items' => array_reverse($itemsArray),
        ]);
        $this->assertResponseStatusCodeSame(419);

        $client->request(Request::METHOD_POST, $uri, [
            'csrf_token' => $token,
            'items' => array_reverse($itemsArray),
        ]);

        $client->request(Request::METHOD_POST, $uri, [
            'csrf_token' => $token,
            'items' => $itemsArray,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
    }

    /**
     * This test changes the database contents by deleting a test Item.
     */
    public function testAdminDeleteItem(): void
    {
        $client = $this->authAsAdmin($this);

        $item = $this->getRepository($client, Menu::class)
            ->findOneBy([
                'url' => self::URL,
            ])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/menu');
        $client->submit($crawler->filter('#delete-form-'.$item)->form());
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $this->assertNull($this->getRepository($client, Menu::class)->findOneBy([
            'url' => self::URL,
        ]));
    }
}
