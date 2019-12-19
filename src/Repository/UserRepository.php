<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

final class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function countAll(): int
    {
        $count = $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count;
    }

    public function findCount(): int
    {
        $cache = new FilesystemAdapter();

        $count = $cache->get('users_count', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->countAll();
        });

        return (int) $count;
    }
}
