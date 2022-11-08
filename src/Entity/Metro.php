<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CityTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityNameTrait;
use App\Entity\Traits\PropertyTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: 'App\Repository\MetroRepository')]
#[UniqueEntity('slug')]
class Metro
{
    use CityTrait;
    use EntityIdTrait;
    use EntityNameTrait;
    use PropertyTrait;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\City', inversedBy: 'metro_stations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city;

    #[ORM\OneToMany(mappedBy: 'metro_station', targetEntity: 'App\Entity\Property')]
    private $properties;

    public const GETTER = 'getMetroStation';
    public const SETTER = 'setMetroStation';
}
