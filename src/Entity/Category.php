<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityNameTrait;
use App\Entity\Traits\PropertyTrait;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity('slug')]
class Category
{
    use EntityIdTrait;
    use EntityNameTrait;
    use PropertyTrait;

    final public const MAPPED_BY = 'category';
    final public const GETTER = 'getCategory';
    final public const SETTER = 'setCategory';
}
