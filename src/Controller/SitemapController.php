<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SitemapController extends AbstractController
{
    private const DEFAULTS = ['_format' => 'xml'];

    #[Route(path: '/sitemap.xml', name: 'sitemap', defaults: self::DEFAULTS)]
    public function siteMap(): Response
    {
        return $this->render('sitemap/sitemap.xml.twig', []);
    }

    #[Route(path: '/sitemap/cities.xml', name: 'cities_sitemap', defaults: self::DEFAULTS)]
    public function cities(CityRepository $cityRepository): Response
    {
        $cities = $cityRepository->findAll();

        return $this->render('sitemap/cities.xml.twig', [
            'cities' => $cities,
        ]);
    }

    #[Route(path: '/sitemap/properties.xml', name: 'properties_sitemap', defaults: self::DEFAULTS)]
    public function properties(PropertyRepository $propertyRepository): Response
    {
        $properties = $propertyRepository->findAllPublished();

        return $this->render('sitemap/properties.xml.twig', [
            'properties' => $properties,
        ]);
    }
}
