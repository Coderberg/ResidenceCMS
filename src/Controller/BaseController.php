<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Setting;
use App\Entity\Locality;
use App\Entity\Operation;
use App\Entity\Category;

class BaseController extends AbstractController
{
    public function settings()
    {
        return $this->getDoctrine()->getRepository(Setting::class)
            ->findAll()[0];
    }

    public function searchFields()
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
            'operations' => $operations
        ];
    }
}
