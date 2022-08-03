<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Property;

class DeletePhotos
{
    public function __construct(private Property $property)
    {
    }

    public function getProperty(): Property
    {
        return $this->property;
    }
}
