<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PropertyDescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PropertyDescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyDescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyDescription[]    findAll()
 * @method PropertyDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyDescription::class);
    }

    // /**
    //  * @return PropertyDescription[] Returns an array of PropertyDescription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PropertyDescription
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
