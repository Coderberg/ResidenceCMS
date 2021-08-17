<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityMetaTrait;
use App\Repository\PropertyDescriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertyDescriptionRepository::class)
 */
class PropertyDescription
{
    use EntityIdTrait;
    use EntityMetaTrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\OneToOne(targetEntity=Property::class, inversedBy="propertyDescription", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $property;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(Property $property): self
    {
        $this->property = $property;

        return $this;
    }
}
