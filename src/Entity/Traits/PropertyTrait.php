<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Property;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use JetBrains\PhpStorm\Pure;

trait PropertyTrait
{
    #[Pure]
    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

       protected function getProperties(): PersistentCollection
       {
           return $this->properties;
       }

       protected function attachProperty(Property $property, callable $setter): self
       {
           if (!$this->properties->contains($property)) {
               $this->properties[] = $property;
               $property->$setter($this);
           }

           return $this;
       }

       protected function detachProperty(Property $property, callable $getter, callable $setter): self
       {
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
