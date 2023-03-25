<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Photo;
use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    public function reorderPhotos(Property $property, array $ids): void
    {
        $i = 1;

        foreach ($ids as $id) {
            $this->createQueryBuilder('i')
                ->update(Photo::class, 'p')
                ->set('p.sort_order', $i)
                ->where('p.id = ?1')
                ->andWhere('p.property = ?2')
                ->setParameter(1, $id)
                ->setParameter(2, $property->getId())
                ->getQuery()
                ->execute();

            ++$i;
        }
    }
}
