<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\City;
use Doctrine\ORM\Mapping as ORM;

trait CityTrait
{
    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: self::INVERSED_BY)]
    #[ORM\JoinColumn(nullable: false)]
    private $city;

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
