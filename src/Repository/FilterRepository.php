<?php

declare(strict_types=1);

namespace App\Repository;

use Knp\Component\Pager\Pagination\PaginationInterface;

final class FilterRepository extends PropertyRepository
{
    public function findByFilter(array $params): PaginationInterface
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where("p.state = 'published'");

        // City
        if ($params['city'] > 0) {
            $qb->andWhere('p.city = '.(int) $params['city']);
        }

        // Deal Type
        if ($params['deal_type'] > 0) {
            $qb->andWhere('p.deal_type = '.(int) $params['deal_type']);
        }

        // Category
        if ($params['category'] > 0) {
            $qb->andWhere('p.category = '.(int) $params['category']);
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

        // Sort by
        if (\in_array($params['sort_by'], ['id', 'priority_number'], true)) {
            $qb->orderBy('p.'.$params['sort_by'], 'DESC');
        } else {
            $qb->orderBy('p.priority_number', 'DESC');
        }

        return $this->createPaginator($qb->getQuery(), $params['page']);
    }
}
