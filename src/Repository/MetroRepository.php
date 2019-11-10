<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Metro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Metro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Metro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Metro[]    findAll()
 * @method Metro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Metro::class);
    }

    // /**
    //  * @return Metro[] Returns an array of Metro objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Metro
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
