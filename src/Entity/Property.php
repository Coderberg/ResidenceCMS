<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 */
class Property
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DealType", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deal_type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Neighborhood", inversedBy="properties")
     * @ORM\JoinColumn(nullable=true)
     */
    private $neighborhood;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta_title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $meta_description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $bathrooms_number;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $bedrooms_number;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $max_guests;

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

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $show_map;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $price_type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $available_now;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "pending"})
     */
    private $state = 'pending ';

    /**
     * @ORM\Column(type="datetime")
     */
    private $published_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="property", orphanRemoval=true)
     * @ORM\OrderBy({"sort_order" = "ASC"})
     */
    private $photos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Feature", inversedBy="properties")
     */
    private $features;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Metro", inversedBy="properties")
     * @ORM\JoinColumn(nullable=true)
     */
    private $metro_station;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority_number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\District", inversedBy="properties")
     * @ORM\JoinColumn(nullable=true)
     */
    private $district;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

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

    public function setMetaDescription(string $meta_description): self
    {
        $this->meta_description = $meta_description;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeInterface $published_at): self
    {
        $this->published_at = $published_at;

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

    public function getNeighborhood(): ?Neighborhood
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(?Neighborhood $neighborhood): self
    {
        $this->neighborhood = $neighborhood;

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

    public function getMetroStation(): ?Metro
    {
        return $this->metro_station;
    }

    public function setMetroStation(?Metro $metro_station): self
    {
        $this->metro_station = $metro_station;

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

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district): self
    {
        $this->district = $district;

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
}
