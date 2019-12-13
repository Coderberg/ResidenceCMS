<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Menu;
use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class PageService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function create(Page $page)
    {
        // Save page
        $this->save($page);
        $this->clearCache();

        // Add a menu item
        if (true === $page->getShowInMenu()) {
            $menu = new Menu();
            $menu->setTitle($page->getTitle());
            $menu->setUrl('/info/'.$page->getSlug());
            $this->save($menu);
        }
    }

    public function countAll(): int
    {
        $cache = new FilesystemAdapter();

        $count = $cache->get('pages_count', function () {
            return $this->em->getRepository(Page::class)->countAll();
        });

        return (int) $count;
    }

    public function save($object): void
    {
        $this->em->persist($object);
        $this->em->flush();
    }

    public function remove($object): void
    {
        $this->em->remove($object);
        $this->em->flush();
    }

    public function delete(Page $page): void
    {
        // Delete page
        $this->remove($page);
        $this->clearCache();

        // Delete a menu item
        $menu = $this->em->getRepository(Menu::class)->findOneBy(['url' => '/info/'.$page->getSlug()]);
        if ($menu) {
            $this->remove($menu);
        }
    }

    private function clearCache(): void
    {
        $cache = new FilesystemAdapter();
        $cache->delete('pages_count');
    }
}
