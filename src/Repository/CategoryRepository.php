<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function countAll(): int
    {
        $count = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count;
    }

    public function findCount(): int
    {
        $cache = new FilesystemAdapter();

        $count = $cache->get('categories_count', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->countAll();
        });

        return (int) $count;
    }
}
