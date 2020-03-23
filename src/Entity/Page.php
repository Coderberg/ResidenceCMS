<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @UniqueEntity("slug")
 */
class Page
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $show_in_menu;

    /**
     * @ORM\Column(type="boolean")
     */
    private $add_contact_form;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private $contact_email_address;

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
}
