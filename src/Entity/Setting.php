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
     * @ORM\Column(type="string", length=128)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $custom_code;

    /**
     * @ORM\Column(type="integer")
     */
    private $items_per_page;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ymaps_key;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

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

    public function getCustomCode(): ?string
    {
        return $this->custom_code;
    }

    public function setCustomCode(?string $custom_code): self
    {
        $this->custom_code = $custom_code;

        return $this;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->items_per_page;
    }

    public function setItemsPerPage(int $items_per_page): self
    {
        $this->items_per_page = $items_per_page;

        return $this;
    }

    public function getYmapsKey(): ?string
    {
        return $this->ymaps_key;
    }

    public function setYmapsKey(?string $ymaps_key): self
    {
        $this->ymaps_key = $ymaps_key;

        return $this;
    }
}
