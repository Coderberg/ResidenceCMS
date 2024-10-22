<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\Menu;
use App\Entity\Page;
use App\Service\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class PageService extends AbstractService
{
    public function __construct(
        CsrfTokenManagerInterface $tokenManager,
        RequestStack $requestStack,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($tokenManager, $requestStack);
    }

    public function create(Page $page): void
    {
        // Save page
        $this->save($page);
        $this->clearCache('pages_count');
        $this->addFlash('success', 'message.created');

        // Add a menu item
        if (true === $page->getShowInMenu()) {
            $menu = new Menu();
            $menu->setTitle($page->getTitle() ?? '');
            $menu->setLocale($page->getLocale() ?? '');
            $menu->setUrl('/info/'.($page->getSlug() ?? ''));
            $this->save($menu);
        }
    }

    public function save(object $object): void
    {
        $this->em->persist($object);
        $this->em->flush();
    }

    public function remove(object $object): void
    {
        $this->em->remove($object);
        $this->em->flush();
    }

    public function delete(Page $page): void
    {
        // Delete page
        $this->remove($page);
        $this->clearCache('pages_count');
        $this->addFlash('success', 'message.deleted');

        // Delete a menu item
        $menu = $this->em->getRepository(Menu::class)->findOneBy(['url' => '/info/'.($page->getSlug() ?? '')]);
        if (null !== $menu) {
            $this->remove($menu);
        }
    }
}
