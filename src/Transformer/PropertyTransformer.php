<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Entity\Property;
use App\Utils\HtmlHelper;

final class PropertyTransformer
{
    public function contentToHtml(Property $property): Property
    {
        $htmlContent = HtmlHelper::text2Html($property->getPropertyDescription()->getContent());
        $property->setPropertyDescription(
            $property->getPropertyDescription()->setContent($htmlContent)
        );

        return $property;
    }

    public function contentToPlainText(Property $property): Property
    {
        $htmlContent = $property->getPropertyDescription()->getContent();
        $textContent = HtmlHelper::html2Text($htmlContent);
        $property->setPropertyDescription(
            $property->getPropertyDescription()->setContent($textContent)
        );

        return $property;
    }
}
