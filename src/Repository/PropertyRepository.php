<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function countAll(): int
    {
        $count = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count;
    }

    public function findLatest(int $page = 1, string $orderBy = 'priority'): Pagerfanta
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('f')
            ->leftJoin('p.photos', 'f');

        if ('id' === $orderBy) {
            $qb->orderBy('p.id', 'DESC');
        } else {
            $qb->orderBy('p.priority_number', 'DESC');
        }

        return $this->createPaginator($qb->getQuery(), $page);
    }

    private function findLimit(): int
    {
        $repository = $this->getEntityManager()->getRepository('App:Settings');
        $limit = $repository->findOneBy(['setting_name' => 'items_per_page']);

        return (int) $limit->getSettingValue();
    }

    protected function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage($this->findLimit());
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
