<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Entity\Neighborhood;
use App\Service\CityService;
use App\Service\NeighborhoodService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CityController extends BaseController
{
    /**
     * @Route("/city/{slug}", defaults={"page": "1"}, methods={"GET"}, name="city")
     * @param Request             $request
     * @param City                $city
     * @param CityService         $cityService
     * @param Neighborhood        $neighborhood
     * @param NeighborhoodService $neighborhoodService
     *
     * @return Response
     */
    public function index(Request $request, City $city, CityService $cityService, Neighborhood $neighborhood, NeighborhoodService $neighborhoodService): Response
    {
        $searchParams = $cityService->getSearchParams($request, $city);
        $searchParams = array_merge($searchParams, $neighborhoodService->getSearchParams($request, $neighborhood));
        $properties = $cityService->getProperties($searchParams);
        $properties = array_merge($properties, $neighborhoodService->getProperties($searchParams));

        $siteOptions = $cityService->decorateOptions($this->site(), $city);

        return $this->render('property/index.html.twig',
            [
                'site' => $siteOptions,
                'properties' => $properties,
                'searchParams' => $searchParams,
            ]
        );
    }
}
