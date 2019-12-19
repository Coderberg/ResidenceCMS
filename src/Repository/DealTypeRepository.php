<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DealType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @method DealType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DealType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DealType[]    findAll()
 * @method DealType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class DealTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DealType::class);
    }

    public function countAll(): int
    {
        $count = $this->createQueryBuilder('o')
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count;
    }

    public function findCount(): int
    {
        $cache = new FilesystemAdapter();

        $count = $cache->get('deal_types_count', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->countAll();
        });

        return (int) $count;
    }
}
