<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityNameTrait;
use App\Entity\Traits\PropertyTrait;
use App\Repository\DealTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DealTypeRepository::class)]
#[UniqueEntity('slug')]
class DealType
{
    use EntityIdTrait;
    use EntityNameTrait;
    use PropertyTrait;

    final public const MAPPED_BY = 'deal_type';
    final public const GETTER = 'getDealType';
    final public const SETTER = 'setDealType';
}
