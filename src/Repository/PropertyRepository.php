<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PropertyRepository extends ServiceEntityRepository
{
    const NUM_ITEMS = 6;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function countAll()
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findLatest(int $page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('f')
            ->leftJoin('p.photos', 'f')
            ->orderBy('p.id', 'DESC');

        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function findByFilter(int $locality, int $operation, int $category, int $page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('p');

        $qb->Where('p.published = 1');

        // Locality
        if ($locality > 0) {
            $qb->andWhere('p.locality = '.(int) $locality);
        }

        // Operation
        if ($operation > 0) {
            $qb->andWhere('p.operation = '.(int) $operation);
        }

        // Category
        if ($category > 0) {
            $qb->andWhere('p.category = '.(int) $category);
        }

        $qb->orderBy('p.id', 'DESC');

        return $this->createPaginator($qb->getQuery(), $page);
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(self::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
