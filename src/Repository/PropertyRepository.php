<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Property;
use App\Entity\Settings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorInterface $paginator,
        protected Security $security)
    {
        parent::__construct($registry, Property::class);
    }

    public function findAllPublished(): array
    {
        $qb = $this->createQueryBuilder('p');
        $query = $qb->where("p.state = 'published'")
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        return $query->execute();
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

    protected function createPaginator(Query $query, int $page): PaginationInterface
    {
        return $this->paginator->paginate($query, $page, $this->findLimit());
    }
}
