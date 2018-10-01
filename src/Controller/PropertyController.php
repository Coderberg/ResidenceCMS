<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PropertyRepository;
use App\Entity\Setting;
use App\Entity\Property;
use App\Entity\Locality;
use App\Entity\Operation;
use App\Entity\Category;

final class PropertyController extends AbstractController
{
    /**
     * @Route("/", defaults={"page": "1"}, methods={"GET"}, name="property")
     * @Route("/page/{page<[1-9]\d*>}", methods={"GET"}, name="property_paginated")
     */
    public function index(?int $page)
    {
        // Get settings
        $repository = $this->getDoctrine()->getRepository(Setting::class);
        $settings = $repository->findSettings();

        // Get localities
        $repository = $this->getDoctrine()->getRepository(Locality::class);
        $localities = $repository->findAll();

        // Get categories
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();

        // Get operations
        $repository = $this->getDoctrine()->getRepository(Operation::class);
        $operations = $repository->findAll();

        // Get properties
        $repository = $this->getDoctrine()->getRepository(Property::class);
        $properties = $repository->findLatest($page);

        return $this->render('property/index.html.twig', [
            'settings' => $settings,
            'localities' => $localities,
            'operations' => $operations,
            'categories' => $categories,
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/property/{id<\d+>}", methods={"GET"}, name="property_show")
     *
     */
    public function propertyShow(Property $property): Response
    {
        // Get localities
        $repository = $this->getDoctrine()->getRepository(Locality::class);
        $localities = $repository->findAll();

        // Get categories
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();

        // Get operations
        $repository = $this->getDoctrine()->getRepository(Operation::class);
        $operations = $repository->findAll();

        return $this->render('property/show.html.twig', [
                'localities' => $localities,
                'operations' => $operations,
                'categories' => $categories,
                'property' => $property,
                'number_of_photos' => count($property->getPhotos())
            ]
        );
    }

    /**
     * @Route("/search", defaults={"page": "1"}, methods={"GET"}, name="property_search")
     * @Route("/search/page/{page<[1-9]\d*>}", methods={"GET"}, name="property_search_paginated")
     */
    public function search(Request $request, PropertyRepository $properties, ?int $page): Response
    {
        $locality_id = $request->query->get('locality', 0);
        $operation_id = $request->query->get('operation', 0);
        $category_id = $request->query->get('category', 0);

        // Query
        $foundProperties = $properties->findByFilter($locality_id, $operation_id, $category_id, $page);

        return $this->render('property/search.html.twig', [
            'properties' => $foundProperties
        ]);
    }
}
