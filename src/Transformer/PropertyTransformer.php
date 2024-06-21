<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Entity\Property;
use App\Utils\HtmlHelper;

final class PropertyTransformer
{
    public function contentToHtml(Property $property): Property
    {
        return $this->transformContent($property, HtmlHelper::text2Html(...));
    }

    public function contentToPlainText(Property $property): Property
    {
        return $this->transformContent($property, HtmlHelper::html2Text(...));
    }

    public function removeScriptsFromHtml(Property $property): Property
    {
        return $this->transformContent($property, HtmlHelper::removeScriptsFromHtml(...));
    }

    private function transformContent(Property $property, callable $transformFunction): Property
    {
        $content = $property->getPropertyDescription()->getContent();
        $transformedContent = \call_user_func($transformFunction, $content);
        $property->setPropertyDescription(
            $property->getPropertyDescription()->setContent($transformedContent)
        );

        return $property;
    }
}
