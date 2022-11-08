<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CityTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityNameTrait;
use App\Entity\Traits\PropertyTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: 'App\Repository\NeighborhoodRepository')]
#[UniqueEntity('slug')]
class Neighborhood
{
    use CityTrait;
    use EntityIdTrait;
    use EntityNameTrait;
    use PropertyTrait;

    #[ORM\OneToMany(mappedBy: 'neighborhood', targetEntity: 'App\Entity\Property')]
    private $properties;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\City', inversedBy: 'neighborhoods')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city;

    public function addProperty(Property $property): self
    {
        return $this->attachProperty($property, 'setNeighborhood');
    }

    public function removeProperty(Property $property): self
    {
        return $this->detachProperty($property, 'getNeighborhood', 'setNeighborhood');
    }
}
