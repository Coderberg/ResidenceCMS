<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function countAll()
    {
        $cache = new FilesystemCache();

        if (!$cache->has('categories_count')) {
            $count = $this->createQueryBuilder('c')
                ->select('count(c.id)')
                ->getQuery()
                ->getSingleScalarResult();

            $cache->set('categories_count', $count, 3600);
        }

        return $cache->get('categories_count');
    }
}
