<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityNameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MetroRepository")
 * @UniqueEntity("slug")
 */
class Metro
{
    use EntityIdTrait;
    use EntityNameTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="metro_stations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Property", mappedBy="metro_station")
     */
    private $properties;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->setMetroStation($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
            // set the owning side to null (unless already changed)
            if ($property->getMetroStation() === $this) {
                $property->setMetroStation(null);
            }
        }

        return $this;
    }
}
