<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Property;

class DeletePhotos
{
    /**
     * @var Property
     */
    private $property;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    public function getProperty(): Property
    {
        return $this->property;
    }
}
