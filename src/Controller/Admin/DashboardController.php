<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\User;
use App\Service\PageService;
use App\Service\PropertyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(PageService $pageService, PropertyService $propertyService): Response
    {
        // Counting the number of properties
        $properties = $propertyService->countAll();

        // Counting the number of cities
        $city = $this->getDoctrine()
            ->getRepository(City::class)->findCount();

        // Counting the number of dealTypes
        $dealTypes = $this->getDoctrine()
            ->getRepository(DealType::class)->findCount();

        // Counting the number of categories
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)->findCount();

        // Counting the number of pages
        $pages = $pageService->countAll();

        // Counting the number of users
        $users = $this->getDoctrine()
            ->getRepository(User::class)->findCount();

        return $this->render('admin/dashboard/index.html.twig', [
            'number_of_properties' => $properties,
            'number_of_cities' => $city,
            'number_of_deal_types' => $dealTypes,
            'number_of_categories' => $categories,
            'number_of_pages' => $pages,
            'number_of_users' => $users,
        ]);
    }
}
