<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 * @UniqueEntity("slug")
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Property", mappedBy="city")
     */
    private $properties;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\District", mappedBy="city")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $districts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Neighborhood", mappedBy="city")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $neighborhoods;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Metro", mappedBy="city", orphanRemoval=true)
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $metro_stations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta_description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
        $this->neighborhoods = new ArrayCollection();
        $this->metro_stations = new ArrayCollection();
        $this->districts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Property[]
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->setCity($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
            // set the owning side to null (unless already changed)
            if ($property->getCity() === $this) {
                $property->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Neighborhood[]
     */
    public function getNeighborhoods(): Collection
    {
        return $this->neighborhoods;
    }

    public function addNeighborhood(Neighborhood $neighborhood): self
    {
        if (!$this->neighborhoods->contains($neighborhood)) {
            $this->neighborhoods[] = $neighborhood;
            $neighborhood->setCity($this);
        }

        return $this;
    }

    public function removeNeighborhood(Neighborhood $neighborhood): self
    {
        if ($this->neighborhoods->contains($neighborhood)) {
            $this->neighborhoods->removeElement($neighborhood);
            // set the owning side to null (unless already changed)
            if ($neighborhood->getCity() === $this) {
                $neighborhood->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Metro[]
     */
    public function getMetroStations(): Collection
    {
        return $this->metro_stations;
    }

    public function addMetroStation(Metro $metroStation): self
    {
        if (!$this->metro_stations->contains($metroStation)) {
            $this->metro_stations[] = $metroStation;
            $metroStation->setCity($this);
        }

        return $this;
    }

    public function removeMetroStation(Metro $metroStation): self
    {
        if ($this->metro_stations->contains($metroStation)) {
            $this->metro_stations->removeElement($metroStation);
            // set the owning side to null (unless already changed)
            if ($metroStation->getCity() === $this) {
                $metroStation->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|District[]
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    public function addDistrict(District $district): self
    {
        if (!$this->districts->contains($district)) {
            $this->districts[] = $district;
            $district->setCity($this);
        }

        return $this;
    }

    public function removeDistrict(District $district): self
    {
        if ($this->districts->contains($district)) {
            $this->districts->removeElement($district);
            // set the owning side to null (unless already changed)
            if ($district->getCity() === $this) {
                $district->setCity(null);
            }
        }

        return $this;
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
