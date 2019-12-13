<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PropertyRepository;
use App\Service\URLService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SitemapController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $propertyRepository;

    /**
     * @var URLService
     */
    private $urlService;

    public function __construct(PropertyRepository $propertyRepository, URLService $urlService)
    {
        $this->propertyRepository = $propertyRepository;
        $this->urlService = $urlService;
    }

    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function siteMap(): Response
    {
        $properties = $this->propertyRepository->findAll();

        foreach ($properties as $property) {
            $urls[] = [
                'loc' => $this->urlService->generateCanonical($property),
                'lastmod' => $property->getPublishedAt()->format('Y-m-d\TH:i:s+00:00'),
                'priority' => '0.80',
            ];
        }

        return $this->render('sitemap/sitemap.xml.twig', [
            'urls' => $urls,
        ]);
    }
}
