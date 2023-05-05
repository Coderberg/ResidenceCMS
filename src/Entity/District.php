<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CityTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityNameTrait;
use App\Entity\Traits\PropertyTrait;
use App\Repository\DistrictRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DistrictRepository::class)]
#[UniqueEntity('slug')]
class District
{
    use CityTrait;
    use EntityIdTrait;
    use EntityNameTrait;
    use PropertyTrait;

    final public const MAPPED_BY = 'district';
    final public const INVERSED_BY = 'districts';
    final public const GETTER = 'getDistrict';
    final public const SETTER = 'setDistrict';
}
