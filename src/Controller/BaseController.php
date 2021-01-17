<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\Feature;
use App\Entity\Menu;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
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
        $request = $this->get('request_stack')->getCurrentRequest();

        return [
            'menu' => $this->getDoctrine()->getRepository(Menu::class)
                ->findBy([
                    'locale' => $request->getLocale(),
                ]),
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

        // Get features
        $features = $this->getDoctrine()
            ->getRepository(Feature::class)->findAll();

        return [
            'cities' => $cities,
            'features' => $features,
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
