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

    public const MAPPED_BY = 'neighborhood';
    public const INVERSED_BY = 'neighborhoods';
    public const GETTER = 'getNeighborhood';
    public const SETTER = 'setNeighborhood';
}
