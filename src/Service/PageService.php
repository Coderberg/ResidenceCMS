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
        $this->em->persist($page);
        $this->em->flush();

        // Clear cache
        $cache = new FilesystemAdapter();
        $cache->delete('pages_count');

        // Add a menu item
        if (true === $page->getShowInMenu()) {
            $menu = new Menu();
            $menu->setTitle($page->getTitle());
            $menu->setUrl('/info/'.$page->getSlug());
            $this->em->persist($menu);
            $this->em->flush();
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

    public function delete(Page $page)
    {
        // Delete page
        $this->em->remove($page);
        $this->em->flush();

        // Clear cache
        $cache = new FilesystemAdapter();
        $cache->delete('pages_count');

        // Delete a menu item
        $menu = $this->em->getRepository(Menu::class)->findOneBy(['url' => '/info/'.$page->getSlug()]);
        if ($menu) {
            $this->em->remove($menu);
            $this->em->flush();
        }
    }
}
