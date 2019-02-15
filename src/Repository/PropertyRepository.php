<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findCount(): int
    {
        $cache = new FilesystemCache();

        if (!$cache->has('properties_count')) {
            $cache->set('properties_count', $this->countAll(), 3600);
        }

        return $cache->get('properties_count');
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

    private function findLimit(): int
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT s.items_per_page FROM App\Entity\Setting s");

        return $query->getSingleScalarResult();
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage($this->findLimit());
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
