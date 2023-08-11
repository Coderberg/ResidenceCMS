<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityMetaTrait;
use App\Entity\Traits\EntityNameTrait;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[UniqueEntity('slug')]
class City
{
    use EntityIdTrait;
    use EntityMetaTrait;
    use EntityNameTrait;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Property::class)]
    private $properties;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: District::class)]
    #[ORM\OrderBy(['name' => 'ASC'])]
    private $districts;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Neighborhood::class)]
    #[ORM\OrderBy(['name' => 'ASC'])]
    private $neighborhoods;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Metro::class, orphanRemoval: true)]
    #[ORM\OrderBy(['name' => 'ASC'])]
    private $metro_stations;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $title = null;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
        $this->neighborhoods = new ArrayCollection();
        $this->metro_stations = new ArrayCollection();
        $this->districts = new ArrayCollection();
    }

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
