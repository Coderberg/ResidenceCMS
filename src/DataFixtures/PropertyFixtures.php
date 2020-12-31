<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Property;
use App\Utils\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class PropertyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getPropertyData() as [$author, $dealType, $category, $bedrooms, $guests, $city, $district,
                 $neighborhood, $title, $address, $latitude, $longitude, $price, $priceType, ]) {
            $property = new Property();
            $property->setAuthor($author);
            $property->setDealType($dealType);
            $property->setCategory($category);
            $property->setBedroomsNumber($bedrooms);
            $property->setMaxGuests($guests);
            $property->setCity($city);
            $property->setNeighborhood($neighborhood);
            $property->setDistrict($district);
            $property->setTitle($title);
            $property->setMetaDescription($title);
            $property->setSlug(Slugger::slugify($title));
            $property->setContent($this->getPropertyContent());
            $property->setAddress($address);
            $property->setLatitude($latitude);
            $property->setLongitude($longitude);
            $property->setShowMap(true);
            $property->setPrice($price);
            $property->setPriceType($priceType);
            $property->setState('published');
            $property->setCreatedAt(new \DateTime('now'));
            $property->setUpdatedAt(new \DateTime('now'));
            $property->addFeature($this->getReference('Климатизация'));
            $property->addFeature($this->getReference('Балкон'));
            $property->addFeature($this->getReference('Противопожарна аларма'));
            $property->addFeature($this->getReference('Интериорни врати'));
            $property->addFeature($this->getReference('Защитен паркинг'));
            $property->setPriorityNumber(0);
            $manager->persist($property);
            $this->addReference(Slugger::slugify($title), $property);
        }
        $manager->flush();
    }

    private function getPropertyData(): array
    {
        return [
            /*
                $propertyData = [$author, $dealType, $category, $bedrooms, $guests, $city,
                                $district $neighborhood, $title, $address,
                                $latitude, $longitude, $price, $priceType];
            */
            [
                $this->getReference('admin'),
                $this->getReference('Продава'),
                $this->getReference('Вила'),
                5,
                null,
                $this->getReference('Варна'),
                $this->getReference('Община Варна'),
                $this->getReference('м-т Боровец - Юг'),
                'Красива вила за продажба в м-т Боровец - Юг',
                'Боровец - Юг',
                '43.1467016', '27.8934756', 1000, 'кв. м.',
            ],
        ];
    }

    private function getPropertyContent(): string
    {
        return '<p>Много красива вила, находяща се на 13 км. от центъра на Варна в местността Боровец - Юг</p>';
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            CityFixtures::class,
            DealTypeFixtures::class,
            DistrictFixtures::class,
            FeatureFixtures::class,
            NeighborhoodFixtures::class,
            UserFixtures::class,
        ];
    }
}
