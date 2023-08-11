<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Repository\MenuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table]
#[ORM\UniqueConstraint(name: 'url_locale_unique_key', columns: ['url', 'locale'])]
#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[UniqueEntity(['url', 'locale'])]
class Menu
{
    use EntityIdTrait;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 2)]
    private string $locale;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $sort_order = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $url = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $nofollow = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $new_tab = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getNofollow(): ?bool
    {
        return $this->nofollow;
    }

    public function setNofollow(?bool $nofollow): self
    {
        $this->nofollow = $nofollow;

        return $this;
    }

    public function getNewTab(): ?bool
    {
        return $this->new_tab;
    }

    public function setNewTab(?bool $new_tab): self
    {
        $this->new_tab = $new_tab;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }
}
