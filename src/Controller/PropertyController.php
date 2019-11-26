<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use App\Service\URLService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PropertyController extends BaseController
{
    /**
     * @Route("/", defaults={"page": "1"}, methods={"GET"}, name="property")
     * @Route("/page/{page<[1-9]\d*>}", methods={"GET"}, name="property_paginated")
     */
    public function index(?int $page): Response
    {
        $properties = $this->getDoctrine()
            ->getRepository(Property::class)->findLatest($page ?? 1);

        return $this->render('property/index.html.twig',
            [
                'site' => $this->site(),
                'properties' => $properties,
                'page' => $page,
            ]
        );
    }

    /**
     * @Route("/search", defaults={"page": "1"}, methods={"GET"}, name="property_search")
     * @Route("/search/page/{page<[1-9]\d*>}", methods={"GET"}, name="property_search_paginated")
     */
    public function search(Request $request, PropertyRepository $properties, ?int $page): Response
    {
        $cityId = $request->query->get('city', 0);
        $dealTypeId = $request->query->get('deal_type', 0);
        $categoryId = $request->query->get('category', 0);
        $numberOfBedrooms = $request->query->get('bedrooms', 0);

        return $this->render('property/search.html.twig',
            [
                'site' => $this->site(),
                'properties' => $properties->findByFilter(
                    (int) $cityId,
                    (int) $dealTypeId,
                    (int) $categoryId,
                    (int) $numberOfBedrooms,
                    ($page ?? 1)
                ),
                'page' => $page,
            ]
        );
    }

    /**
     * @Route("/map", methods={"GET"}, name="map_view")
     */
    public function mapView(Request $request, PropertyRepository $repository): Response
    {
        return $this->render('property/map.html.twig',
            [
                'site' => $this->site(),
                'properties' => $repository->findAll(),
            ]
        );
    }

    /**
     * @Route("/{citySlug}/{slug}/{id<\d+>}", methods={"GET"}, name="property_show")
     */
    public function propertyShow(Request $request, URLService $url, Property $property, string $citySlug, string $slug): Response
    {
        if (!$url->isCanonical($property, $citySlug, $slug)) {
            return $this->redirect($url->generateCanonical($property), 301);
        } elseif (preg_match('/'.$request->getHost().'/', ($request->server->getHeaders()['REFERER']) ?? '')) {
            $show_back_button = true;
        }

        return $this->render('property/show.html.twig',
            [
                'site' => $this->site(),
                'property' => $property,
                'number_of_photos' => \count($property->getPhotos()),
                'show_back_button' => $show_back_button ?? false,
            ]
        );
    }

    /**
     * @Route("/property/{id<\d+>}", methods={"GET"}, name="property_show_short_link")
     */
    public function propertyShowShort(URLService $url, Property $property)
    {
        return $this->redirect($url->generateCanonical($property), 301);
    }
}
