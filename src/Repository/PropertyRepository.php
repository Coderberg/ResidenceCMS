<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function findLatest(int $page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('f')
            ->leftJoin('p.photos', 'f')
            ->orderBy('p.id', 'DESC');

        return $this->createPaginator($qb->getQuery(), $page);

    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(6);
        $paginator->setCurrentPage($page);
        return $paginator;
    }

    /**
     * @return Property[]
     *
     */
    public function findByFilter(int $locality, int $operation, int $category): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->Where('p.published = 1');

        // Locality
        if ($locality > 0) {
            $queryBuilder
                ->andWhere('p.locality = ' . (int)$locality);
        }

        // Operation
        if ($operation > 0) {
            $queryBuilder
                ->andWhere('p.operation = ' . (int)$operation);
        }

        // Category
        if ($category > 0) {
            $queryBuilder
                ->andWhere('p.category = ' . (int)$category);
        }

        return $queryBuilder
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(24)
            ->getQuery()
            ->getResult();
    }
}
