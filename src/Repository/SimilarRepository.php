<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Property;

final class SimilarRepository extends PropertyRepository
{
    const NUM_ITEMS = 6;

    public function findSimilarProperties(Property $property): array
    {
        if (!$this->isModuleEnabled()) {
            return [];
        }

        if ($property->getNeighborhood()) {
            // Find in a small area
            $result = $this->findByArea($property, 'neighborhood');

            if (empty($result) && $property->getDistrict()) {
                // Find in a larger area
                $result = $this->findByArea($property);
            }

            return $result;
        } elseif ($property->getDistrict()) {
            return $this->findByArea($property);
        }

        return [];
    }

    private function findByArea(Property $property, string $area = 'district'): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where("p.state = 'published'")
            ->andWhere('p.id != '.(int) ($property->getId()))
            ->andWhere('p.deal_type = '.(int) ($property->getDealType()->getId()))
            ->andWhere('p.category = '.(int) ($property->getCategory()->getId()));

        if ('neighborhood' === $area) {
            $qb->andWhere('p.neighborhood = '.(int) ($property->getNeighborhood()->getId()));
        } else {
            $qb->andWhere('p.district = '.(int) ($property->getDistrict()->getId()));
        }

        return $qb->getQuery()->setMaxResults(self::NUM_ITEMS)->getResult();
    }

    private function isModuleEnabled(): bool
    {
        $repository = $this->getEntityManager()->getRepository('App:Settings');
        $state = $repository->findOneBy(['setting_name' => 'show_similar_properties']);

        return '1' === $state->getSettingValue();
    }
}
