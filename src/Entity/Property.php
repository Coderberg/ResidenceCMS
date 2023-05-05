<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CityTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityLocationTrait;
use App\Entity\Traits\EntityTimestampableTrait;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    use CityTrait;
    use EntityIdTrait;
    use EntityLocationTrait;
    use EntityTimestampableTrait;

    final public const INVERSED_BY = 'properties';

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\ManyToOne(targetEntity: DealType::class, inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    private $deal_type;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private $slug;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private $bathrooms_number;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private $bedrooms_number;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private $max_guests;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private $show_map;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private $price;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private $price_type;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private $available_now;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['default' => 'pending'])]
    private string $state = 'published';

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: Photo::class, orphanRemoval: true)]
    #[ORM\OrderBy(['sort_order' => 'ASC'])]
    private $photos;

    #[ORM\ManyToMany(targetEntity: Feature::class, inversedBy: 'properties')]
    private $features;

    #[ORM\Column(type: Types::INTEGER)]
    private $priority_number;

    #[ORM\OneToOne(mappedBy: 'property', targetEntity: PropertyDescription::class, cascade: ['persist', 'remove'])]
    private $propertyDescription;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDealType(): ?DealType
    {
        return $this->deal_type;
    }

    public function setDealType(?DealType $dealType): self
    {
        $this->deal_type = $dealType;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
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

    public function getBathroomsNumber(): ?int
    {
        return $this->bathrooms_number;
    }

    public function setBathroomsNumber(?int $bathrooms_number): self
    {
        $this->bathrooms_number = $bathrooms_number;

        return $this;
    }

    public function getBedroomsNumber(): ?int
    {
        return $this->bedrooms_number;
    }

    public function setBedroomsNumber(?int $bedrooms_number): self
    {
        $this->bedrooms_number = $bedrooms_number;

        return $this;
    }

    public function getShowMap(): ?bool
    {
        return $this->show_map;
    }

    public function setShowMap(?bool $show_map): self
    {
        $this->show_map = $show_map;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceType(): ?string
    {
        return $this->price_type;
    }

    public function setPriceType(?string $price_type): self
    {
        $this->price_type = $price_type;

        return $this;
    }

    public function getAvailableNow(): ?bool
    {
        return $this->available_now;
    }

    public function setAvailableNow(?bool $available_now): self
    {
        $this->available_now = $available_now;

        return $this;
    }

    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProperty($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getProperty() === $this) {
                $photo->setProperty(null);
            }
        }

        return $this;
    }

    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(Feature $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features[] = $feature;
        }

        return $this;
    }

    public function removeFeature(Feature $feature): self
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
        }

        return $this;
    }

    public function getPriorityNumber(): ?int
    {
        return $this->priority_number;
    }

    public function setPriorityNumber(?int $priority_number): self
    {
        $this->priority_number = $priority_number;

        return $this;
    }

    public function getMaxGuests(): ?int
    {
        return $this->max_guests;
    }

    public function setMaxGuests(?int $max_guests): self
    {
        $this->max_guests = $max_guests;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function isPublished(): bool
    {
        return 'published' === $this->state;
    }

    public function getPropertyDescription(): ?PropertyDescription
    {
        return $this->propertyDescription;
    }

    public function setPropertyDescription(PropertyDescription $propertyDescription): self
    {
        // set the owning side of the relation if necessary
        if ($propertyDescription->getProperty() !== $this) {
            $propertyDescription->setProperty($this);
        }

        $this->propertyDescription = $propertyDescription;

        return $this;
    }
}
