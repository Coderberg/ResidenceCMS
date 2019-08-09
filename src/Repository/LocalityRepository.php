<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Locality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @method Locality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Locality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Locality[]    findAll()
 * @method Locality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class LocalityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Locality::class);
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findCount(): int
    {
        $cache = new FilesystemAdapter();

        $count = $cache->get('localities_count', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->countAll();
        });

        return (int) $count;
    }
}
