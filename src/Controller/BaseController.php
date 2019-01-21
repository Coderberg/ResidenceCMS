<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Locality;
use App\Entity\Operation;
use App\Entity\Page;
use App\Entity\Setting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class BaseController extends AbstractController
{
    public function settings()
    {
        return $this->getDoctrine()->getRepository(Setting::class)
            ->findAll()[0];
    }

    public function pages(): array
    {
        return [
            'pages' => $this->getDoctrine()->getRepository(Page::class)
                ->findMenuItems(),
        ];
    }

    public function searchFields(): array
    {
        // Get localities
        $localities = $this->getDoctrine()
            ->getRepository(Locality::class)->findAll();

        // Get categories
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)->findAll();

        // Get operations
        $operations = $this->getDoctrine()
            ->getRepository(Operation::class)->findAll();

        return [
            'localities' => $localities,
            'categories' => $categories,
            'operations' => $operations,
        ];
    }

    public function site(): array
    {
        $normalizer = new GetSetMethodNormalizer();

        $settings = $normalizer->normalize($this->settings());

        $fields = $this->searchFields();

        $pages = $this->pages();

        return array_merge($settings, $fields, $pages);
    }
}
