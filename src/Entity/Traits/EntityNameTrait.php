<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait EntityNameTrait
{
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_-]+$/')]
    private ?string $slug = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
