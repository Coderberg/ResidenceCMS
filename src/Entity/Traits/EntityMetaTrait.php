<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait EntityMetaTrait
{
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $meta_title = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $meta_description = null;

    public function getMetaTitle(): ?string
    {
        return $this->meta_title;
    }

    public function setMetaTitle(?string $meta_title): self
    {
        $this->meta_title = $meta_title;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(?string $meta_description): self
    {
        $this->meta_description = $meta_description;

        return $this;
    }
}
