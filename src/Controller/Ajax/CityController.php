<?php

declare(strict_types=1);

namespace App\Controller\Ajax;

use App\Entity\City;
use App\Repository\DistrictRepository;
use App\Repository\MetroRepository;
use App\Repository\NeighborhoodRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class CityController extends AbstractController implements AjaxController
{
    #[Route(path: '/city/{id<\d+>}.json', name: 'city_json', methods: ['GET'])]
    public function show(
        City $city,
        MetroRepository $metroRepository,
        DistrictRepository $districtRepository,
        NeighborhoodRepository $neighborhoodRepository,
    ): JsonResponse {
        return $this->json([
            'city' => $city->getName(),
            'districts' => $this->find($city, $districtRepository),
            'neighborhoods' => $this->find($city, $neighborhoodRepository),
            'metro_stations' => $this->find($city, $metroRepository),
        ]);
    }

    private function find(City $city, ServiceEntityRepositoryInterface $repository): array
    {
        return array_map(fn ($entity) => [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
        ],
            $repository->findBy(['city' => $city]));
    }
}
