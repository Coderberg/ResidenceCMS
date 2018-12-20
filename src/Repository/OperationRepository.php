<?php

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * @method Operation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operation[]    findAll()
 * @method Operation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class OperationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    public function countAll()
    {
        $cache = new FilesystemCache();

        if (!$cache->has('operations_count')) {
            $count = $this->createQueryBuilder('o')
                ->select('count(o.id)')
                ->getQuery()
                ->getSingleScalarResult();

            $cache->set('operations_count', $count, 3600);
        }

        return $cache->get('operations_count');
    }
}
