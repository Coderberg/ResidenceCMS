<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Property;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait PropertyTrait
{
    #[ORM\OneToMany(mappedBy: self::MAPPED_BY, targetEntity: Property::class)]
    private Collection $properties;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    protected function getProperties(): Collection
    {
        return $this->properties;
    }

    protected function addProperty(Property $property): self
    {
        /* @var callable $setter */
        $setter = self::SETTER;

        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->$setter($this);
        }

        return $this;
    }

    protected function removeProperty(Property $property): self
    {
        /* @var callable $setter */
        $setter = self::SETTER;

        /* @var callable $getter */
        $getter = self::GETTER;

        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
            // set the owning side to null (unless already changed)
            if ($property->$getter() === $this) {
                $property->$setter(null);
            }
        }

        return $this;
    }
}
