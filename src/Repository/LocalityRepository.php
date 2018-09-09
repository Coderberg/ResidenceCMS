<?php

namespace App\Repository;

use App\Entity\Locality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Locality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Locality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Locality[]    findAll()
 * @method Locality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocalityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Locality::class);
    }
}
