<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CityTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityNameTrait;
use App\Entity\Traits\PropertyTrait;
use App\Repository\NeighborhoodRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: NeighborhoodRepository::class)]
#[UniqueEntity('slug')]
class Neighborhood
{
    use CityTrait;
    use EntityIdTrait;
    use EntityNameTrait;
    use PropertyTrait;

    final public const MAPPED_BY = 'neighborhood';
    final public const INVERSED_BY = 'neighborhoods';
    final public const GETTER = 'getNeighborhood';
    final public const SETTER = 'setNeighborhood';
}
