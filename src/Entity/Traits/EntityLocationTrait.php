<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\City;
use App\Entity\District;
use App\Entity\Metro;
use App\Entity\Neighborhood;
use Doctrine\ORM\Mapping as ORM;

trait EntityLocationTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\District", inversedBy="properties")
     * @ORM\JoinColumn(nullable=true)
     */
    private $district;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Neighborhood", inversedBy="properties")
     * @ORM\JoinColumn(nullable=true)
     */
    private $neighborhood;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Metro", inversedBy="properties")
     * @ORM\JoinColumn(nullable=true)
     */
    private $metro_station;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude;

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getNeighborhood(): ?Neighborhood
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(?Neighborhood $neighborhood): self
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    public function getMetroStation(): ?Metro
    {
        return $this->metro_station;
    }

    public function setMetroStation(?Metro $metro_station): self
    {
        $this->metro_station = $metro_station;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
