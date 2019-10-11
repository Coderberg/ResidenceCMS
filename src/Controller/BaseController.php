<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Locality;
use App\Entity\Menu;
use App\Entity\Operation;
use App\Entity\Setting;
use App\Transformer\SettingTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     * @var SettingTransformer
     */
    private $transformer;

    public function __construct(SettingTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function settings()
    {
        return $this->getDoctrine()->getRepository(Setting::class)
            ->findAll()[0];
    }

    public function menu(): array
    {
        return [
            'menu' => $this->getDoctrine()->getRepository(Menu::class)
                ->findItems(),
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
        $settings = $this->transformer->transform($this->settings());

        $fields = $this->searchFields();

        $menu = $this->menu();

        return array_merge($settings, $fields, $menu);
    }
}
