<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Entity\Setting;

final class SettingTransformer
{
    /**
     * Transforms an object to an array.
     */
    public function transform(Setting $seting): array
    {
        return [
            'name' => $seting->getName(),
            'title' => $seting->getTitle(),
            'description' => $seting->getDescription(),
            'customCode' => $seting->getCustomCode(),
            'ymapsKey' => $seting->getYmapsKey(),
            'items_per_page' => $seting->getItemsPerPage(),
            'mapCenter' => $seting->getMapCenter(),
            'mapZoom' => $seting->getMapZoom(),
            'currency' => [
                'symbolLeft' => $seting->getCurrency()->getSymbolLeft(),
                'symbolRight' => $seting->getCurrency()->getSymbolRight(),
            ],
        ];
    }
}
