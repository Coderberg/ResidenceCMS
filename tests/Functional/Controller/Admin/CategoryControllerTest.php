<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Category;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CategoryControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const NAME = 'Test';
    private const SLUG = 'test';
    private const EDITED_NAME = 'Edited';

    /**
     * This test changes the database contents by creating a new Category.
     */
    public function testAdminNewCategory(): void
    {
        $client = $this->authAsAdmin($this);
        $crawler = $client->request(Request::METHOD_GET, '/en/admin/category/new');

        $form = $crawler->selectButton('Create category')->form([
            'category[name]' => self::NAME,
            'category[slug]' => self::SLUG,
        ]);
        $client->submit($form);

        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
        $category = $this->getRepository($client, Category::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($category);
        $this->assertSame(self::NAME, $category->getName());
        $this->assertSame(self::SLUG, $category->getSlug());
    }

    /**
     * This test changes the database contents by editing a Category.
     */
    public function testAdminEditCategory(): void
    {
        $client = $this->authAsAdmin($this);

        $category = $this->getRepository($client, Category::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/category/'.$category.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'category[name]' => self::EDITED_NAME,
        ]);

        $client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $editedCategory = $this->getRepository($client, Category::class)
            ->findOneBy([
                'id' => $category,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedCategory->getName());
    }

    /**
     * This test changes the database contents by deleting a test Category.
     */
    public function testAdminDeleteCategory(): void
    {
        $client = $this->authAsAdmin($this);

        $category = $this->getRepository($client, Category::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/category');
        $client->submit($crawler->filter('#delete-form-'.$category)->form());
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $this->assertNull($this->getRepository($client, Category::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
