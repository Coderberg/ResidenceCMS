<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\City;

trait CityTrait
{
    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
