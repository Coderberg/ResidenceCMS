<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DealType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DealType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DealType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DealType[]    findAll()
 * @method DealType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class DealTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DealType::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countAll(): int
    {
        $count = $this->createQueryBuilder('o')
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count;
    }
}
