<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Page;
use App\Entity\Settings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly PaginatorInterface $paginator)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countAll(): int
    {
        $count = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count;
    }

    private function findLimit(): int
    {
        $repository = $this->getEntityManager()->getRepository(Settings::class);
        $limit = $repository->findOneBy(['setting_name' => 'items_per_page']);

        return (int) $limit->getSettingValue();
    }

    public function findLatest(Request $request): PaginationInterface
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');

        return $this->createPaginator($qb->getQuery(), $request);
    }

    private function createPaginator(Query $query, Request $request): PaginationInterface
    {
        $page = $request->query->getInt('page', 1);

        return $this->paginator->paginate($query, $page, $this->findLimit());
    }
}
