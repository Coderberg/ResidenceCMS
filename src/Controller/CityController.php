<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Service\CityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CityController extends BaseController
{
    #[Route(path: '/city/{slug}', defaults: ['page' => 1], methods: ['GET'], name: 'city')]
    public function index(Request $request, City $city, CityService $service): Response
    {
        $searchParams = $service->getSearchParams($request, $city);
        $properties = $service->getProperties($searchParams);
        $siteOptions = $service->decorateOptions($this->site($request), $city);

        return $this->render('property/index.html.twig',
            [
                'site' => $siteOptions,
                'properties' => $properties,
                'searchParams' => $searchParams,
            ]
        );
    }
}
