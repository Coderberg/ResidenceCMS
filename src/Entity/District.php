<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CityTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityNameTrait;
use App\Entity\Traits\PropertyTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: 'App\Repository\DistrictRepository')]
#[UniqueEntity('slug')]
class District
{
    use CityTrait;
    use EntityIdTrait;
    use EntityNameTrait;
    use PropertyTrait;

    #[ORM\OneToMany(mappedBy: 'district', targetEntity: 'App\Entity\Property')]
    private $properties;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\City', inversedBy: 'districts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city;

    public function addProperty(Property $property): self
    {
        return $this->attachProperty($property, 'setDistrict');
    }

    public function removeProperty(Property $property): self
    {
        return $this->detachProperty($property, 'getDistrict', 'setDistrict');
    }
}
