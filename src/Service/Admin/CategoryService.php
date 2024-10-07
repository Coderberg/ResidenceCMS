<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\Category;
use App\Service\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class CategoryService extends AbstractService
{
    public function __construct(
        CsrfTokenManagerInterface $tokenManager,
        RequestStack $requestStack,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($tokenManager, $requestStack);
    }

    public function create(Category $category): void
    {
        $this->save($category);
        $this->clearCache('categories_count');
        $this->addFlash('success', 'message.created');
    }

    public function update(Category $category): void
    {
        $this->save($category);
        $this->addFlash('success', 'message.updated');
    }

    public function remove(Category $category): void
    {
        $this->em->remove($category);
        $this->em->flush();
        $this->clearCache('categories_count');
        $this->addFlash('success', 'message.deleted');
    }

    private function save(Category $category): void
    {
        $this->em->persist($category);
        $this->em->flush();
    }
}
