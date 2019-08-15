<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class MenuRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findItems()
    {
        return $this->findBy([], ['sort_order' => 'ASC']);
    }

    public function reorderItems(array $items): void
    {
        $i = 1;

        foreach ($items as $item) {
            $this->createQueryBuilder('i')
                ->update('App\Entity\Menu', 'm')
                ->set('m.sort_order', $i)
                ->where('m.id = ?1')
                ->setParameter(1, $item)
                ->getQuery()
                ->execute();

            ++$i;
        }
    }
}
