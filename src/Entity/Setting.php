<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 */
class Setting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $homepage_title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $homepage_description;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $homepage_meta_tags;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomepageTitle(): ?string
    {
        return $this->homepage_title;
    }

    public function setHomepageTitle(string $homepage_title): self
    {
        $this->homepage_title = $homepage_title;

        return $this;
    }

    public function getHomepageDescription(): ?string
    {
        return $this->homepage_description;
    }

    public function setHomepageDescription(string $homepage_description): self
    {
        $this->homepage_description = $homepage_description;

        return $this;
    }

    public function getHomepageMetaTags(): ?string
    {
        return $this->homepage_meta_tags;
    }

    public function setHomepageMetaTags(?string $homepage_meta_tags): self
    {
        $this->homepage_meta_tags = $homepage_meta_tags;

        return $this;
    }
}
