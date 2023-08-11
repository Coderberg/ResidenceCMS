<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Repository\PageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table]
#[ORM\UniqueConstraint(name: 'slug_locale_unique_key', columns: ['slug', 'locale'])]
#[ORM\Entity(repositoryClass: PageRepository::class)]
#[UniqueEntity(['slug', 'locale'])]
class Page
{
    use EntityIdTrait;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::STRING, length: 2, options: ['default' => 'en'])]
    private string $locale;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $show_in_menu = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $add_contact_form = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Assert\Email]
    private ?string $contact_email_address = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getShowInMenu(): ?bool
    {
        return $this->show_in_menu;
    }

    public function setShowInMenu(bool $show_in_menu): self
    {
        $this->show_in_menu = $show_in_menu;

        return $this;
    }

    public function getAddContactForm(): ?bool
    {
        return $this->add_contact_form;
    }

    public function setAddContactForm(bool $add_contact_form): self
    {
        $this->add_contact_form = $add_contact_form;

        return $this;
    }

    public function getContactEmailAddress(): ?string
    {
        return $this->contact_email_address;
    }

    public function setContactEmailAddress(?string $contact_email_address): self
    {
        $this->contact_email_address = $contact_email_address;

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
