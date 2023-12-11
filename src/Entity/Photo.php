<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Repository\PhotoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    use EntityIdTrait;

    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: true)]
    private $property;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\File(mimeTypes: ['image/*'])]
    private $photo;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $sort_order = null;

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->sort_order;
    }

    public function setSortOrder(?int $sort_order): self
    {
        $this->sort_order = $sort_order;

        return $this;
    }
}
