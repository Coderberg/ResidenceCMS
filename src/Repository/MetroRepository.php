<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Metro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Metro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Metro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Metro[]    findAll()
 * @method Metro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class MetroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Metro::class);
    }
}
