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
     * @ORM\Column(type="string", length=255)
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
     * @ORM\OneToMany(targetEntity="App\Entity\Area", mappedBy="city")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $areas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Metro", mappedBy="city", orphanRemoval=true)
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $metro_stations;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
        $this->areas = new ArrayCollection();
        $this->metro_stations = new ArrayCollection();
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
     * @return Collection|Area[]
     */
    public function getAreas(): Collection
    {
        return $this->areas;
    }

    public function addArea(Area $area): self
    {
        if (!$this->areas->contains($area)) {
            $this->areas[] = $area;
            $area->setCity($this);
        }

        return $this;
    }

    public function removeArea(Area $area): self
    {
        if ($this->areas->contains($area)) {
            $this->areas->removeElement($area);
            // set the owning side to null (unless already changed)
            if ($area->getCity() === $this) {
                $area->setCity(null);
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
}
