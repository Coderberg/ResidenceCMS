<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Neighborhood;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Neighborhood|null find($id, $lockMode = null, $lockVersion = null)
 * @method Neighborhood|null findOneBy(array $criteria, array $orderBy = null)
 * @method Neighborhood[]    findAll()
 * @method Neighborhood[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class NeighborhoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Neighborhood::class);
    }
}
