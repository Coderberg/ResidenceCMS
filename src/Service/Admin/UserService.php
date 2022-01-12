<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\User;
use App\Service\AbstractService;
use App\Transformer\UserTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class UserService extends AbstractService
{
    private EntityManagerInterface $em;
    private UserTransformer $transformer;

    public function __construct(
        CsrfTokenManagerInterface $tokenManager,
        RequestStack $requestStack,
        EntityManagerInterface $entityManager,
        UserTransformer $transformer
    ) {
        parent::__construct($tokenManager, $requestStack);
        $this->em = $entityManager;
        $this->transformer = $transformer;
    }

    public function create(User $user): void
    {
        $user = $this->transformer->transform($user);
        $this->save($user);
        $this->clearCache('users_count');
        $this->addFlash('success', 'message.created');
    }

    public function update(User $user): void
    {
        $user = $this->transformer->transform($user);
        $this->save($user);
        $this->addFlash('success', 'message.updated');
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
        $this->clearCache('users_count');
        $this->addFlash('success', 'message.deleted');
    }

    private function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}
