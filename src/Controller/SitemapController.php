<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SitemapController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function siteMap(): Response
    {
        $properties = $this->propertyRepository->findAll();

        foreach ($properties as $property) {
            $urls[] = [
                'loc' => $this->generateUrl(
                    'property_show',
                    ['citySlug' => $property->getCity()->getSlug(), 'id' => $property->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'lastmod' => $property->getPublishedAt()->format('Y-m-d\TH:i:s+00:00'),
                'priority' => '0.80',
            ];
        }

        return $this->render('sitemap/sitemap.xml.twig', [
            'urls' => $urls,
        ]);
    }
}
