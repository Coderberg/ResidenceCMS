<?php

declare(strict_types=1);

namespace App\Repository;

use Knp\Component\Pager\Pagination\PaginationInterface;

final class FilterRepository extends PropertyRepository
{
    public function findByFilter(array $params): PaginationInterface
    {
        $qb = $this->createQueryBuilder('p');

        if ($this->security->isGranted('ROLE_ADMIN')) {
            if (\in_array($params['state'], ['published', 'private', 'pending', 'rejected'], true)) {
                $qb->where("p.state = '".$params['state']."'");
            }
        } else {
            $qb->where("p.state = 'published'");
        }

        // Number of bedrooms
        if ($params['bedrooms'] > 3) {
            $qb->andWhere('p.bedrooms_number > 3');
        } elseif ($params['bedrooms'] > 0) {
            $qb->andWhere('p.bedrooms_number = '.(int) $params['bedrooms']);
        }

        // Number of guests
        if ($params['guests'] > 0) {
            $qb->andWhere('p.max_guests >= '.(int) $params['guests']);
        }

        // Additional params
        foreach (['city', 'deal_type', 'category'] as $parameter) {
            if ($params[$parameter] > 0) {
                $qb->andWhere('p.'.$parameter.' = '.(int) $params[$parameter]);
            }
        }

        if ($params['feature'] > 0) {
            $qb->innerJoin('p.features', 'pf');
            $qb->andWhere(':feature MEMBER OF p.features')
                ->setParameter(':feature', (int) $params['feature']);
        }

        // Sort by
        ('id' === $params['sort_by'])
            ? $qb->orderBy('p.id', 'DESC')
            : $qb->orderBy('p.priority_number', 'DESC');

        return $this->createPaginator($qb->getQuery(), $params['page']);
    }
}
