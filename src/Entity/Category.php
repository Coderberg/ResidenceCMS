<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityNameTrait;
use App\Entity\Traits\PropertyTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: 'App\Repository\CategoryRepository')]
#[UniqueEntity('slug')]
class Category
{
    use EntityIdTrait;
    use EntityNameTrait;
    use PropertyTrait;

    public const MAPPED_BY = 'category';
}
