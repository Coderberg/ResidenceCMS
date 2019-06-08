<?php

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

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

    public function countAll(): int
    {
        return $this->createQueryBuilder('o')
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findCount(): int
    {
        $cache = new FilesystemAdapter();

        $count = $cache->get('operations_count', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->countAll();
        });

        return (int) $count;
    }
}
