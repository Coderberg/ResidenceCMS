<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\Menu;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    private function menu(): array
    {
        return [
            'menu' => $this->getDoctrine()->getRepository(Menu::class)
                ->findItems(),
        ];
    }

    private function searchFields(): array
    {
        // Get city
        $cities = $this->getDoctrine()
            ->getRepository(City::class)->findAll();

        // Get categories
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)->findAll();

        // Get deal types
        $dealTypes = $this->getDoctrine()
            ->getRepository(DealType::class)->findAll();

        return [
            'cities' => $cities,
            'categories' => $categories,
            'deal_types' => $dealTypes,
        ];
    }

    public function site(): array
    {
        $settings = $this->settingsRepository->findAllAsArray();

        $fields = $this->searchFields();

        $menu = $this->menu();

        return array_merge($settings, $fields, $menu);
    }
}
