<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Property;
use App\Repository\FilterRepository;
use App\Repository\PropertyRepository;
use App\Repository\SimilarRepository;
use App\Service\URLService;
use App\Transformer\RequestToArrayTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PropertyController extends BaseController
{
    /**
     * @Route("/", defaults={"page": "1"}, methods={"GET"}, name="property")
     */
    public function search(Request $request, FilterRepository $repository, RequestToArrayTransformer $transformer): Response
    {
        $searchParams = $transformer->transform($request);
        $properties = $repository->findByFilter($searchParams);

        return $this->render('property/index.html.twig',
            [
                'site' => $this->site(),
                'properties' => $properties,
                'searchParams' => $searchParams,
            ]
        );
    }

    /**
     * @Route("/map", methods={"GET"}, name="map_view")
     */
    public function mapView(PropertyRepository $repository): Response
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
    public function propertyShow(Request $request, URLService $url, Property $property, SimilarRepository $repository): Response
    {
        if (!$url->isCanonical($property, $request)) {
            return $this->redirect($url->generateCanonical($property), 301);
        } elseif ($url->isRefererFromCurrentHost($request)) {
            $show_back_button = true;
        }

        return $this->render('property/show.html.twig',
            [
                'site' => $this->site(),
                'property' => $property,
                'properties' => $repository->findSimilarProperties($property),
                'number_of_photos' => \count($property->getPhotos()),
                'show_back_button' => $show_back_button ?? false,
            ]
        );
    }

    /**
     * @Route("/property/{id<\d+>}", methods={"GET"}, name="property_show_short_link")
     */
    public function propertyShowShort(URLService $url, Property $property): Response
    {
        return $this->redirect($url->generateCanonical($property), 301);
    }
}
