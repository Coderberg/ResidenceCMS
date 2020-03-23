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
 * @ORM\Entity(repositoryClass="App\Repository\DealTypeRepository")
 * @UniqueEntity("slug")
 */
class DealType
{
    use EntityIdTrait;
    use EntityNameTrait;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Property", mappedBy="deal_type")
     */
    private $properties;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->setDealType($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
            // set the owning side to null (unless already changed)
            if ($property->getDealType() === $this) {
                $property->setDealType(null);
            }
        }

        return $this;
    }
}
