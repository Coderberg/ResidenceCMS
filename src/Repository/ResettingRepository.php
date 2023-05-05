<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Transformer\UserTransformer;
use Doctrine\Persistence\ManagerRegistry;

final class ResettingRepository extends UserRepository
{
    public function __construct(ManagerRegistry $registry, private readonly UserTransformer $transformer)
    {
        parent::__construct($registry);
    }

    public function setPassword(User $user, string $plainPassword): void
    {
        $user->setPassword($plainPassword);
        $user->setConfirmationToken(null);
        $user->setPasswordRequestedAt(null);
        $user = $this->transformer->transform($user);
        $this->save($user);
    }

    public function setToken(User $user, string $token): void
    {
        $user->setConfirmationToken($token);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->save($user);
    }

    private function save(User $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }
}
