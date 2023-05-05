<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CityTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityNameTrait;
use App\Entity\Traits\PropertyTrait;
use App\Repository\MetroRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MetroRepository::class)]
#[UniqueEntity('slug')]
class Metro
{
    use CityTrait;
    use EntityIdTrait;
    use EntityNameTrait;
    use PropertyTrait;

    final public const MAPPED_BY = 'metro_station';
    final public const INVERSED_BY = 'metro_stations';
    final public const GETTER = 'getMetroStation';
    final public const SETTER = 'setMetroStation';
}
