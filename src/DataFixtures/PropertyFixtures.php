<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\District;
use App\Entity\Feature;
use App\Entity\Metro;
use App\Entity\Neighborhood;
use App\Entity\Property;
use App\Entity\PropertyDescription;
use App\Entity\User;
use App\Utils\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class PropertyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getPropertyData() as [$author, $dealType, $category, $bedrooms, $guests, $city, $district,
            $neighborhood, $metro, $title, $address, $latitude, $longitude, $price, $priceType, ]) {
            $property = new Property();
            $property->setAuthor($author);
            $property->setDealType($dealType);
            $property->setCategory($category);
            $property->setBedroomsNumber($bedrooms);
            $property->setMaxGuests($guests);
            $property->setCity($city);
            $property->setNeighborhood($neighborhood);
            $property->setDistrict($district);
            $property->setMetroStation($metro);
            $property->setSlug(Slugger::slugify($title));
            $property->setAddress($address);
            $property->setLatitude($latitude);
            $property->setLongitude($longitude);
            $property->setShowMap(true);
            $property->setPrice($price);
            $property->setPriceType($priceType);
            $property->setState('published');
            $property->setCreatedAt(new \DateTime('now'));
            $property->setUpdatedAt(new \DateTime('now'));
            $property->addFeature($this->getReference('Air conditioning', Feature::class));
            $property->addFeature($this->getReference('Balcony', Feature::class));
            $property->addFeature($this->getReference('Fire Alarm', Feature::class));
            $property->addFeature($this->getReference('High Impact Doors', Feature::class));
            $property->addFeature($this->getReference('Secure parking', Feature::class));
            $property->setPriorityNumber(0);
            $property->setPropertyDescription((new PropertyDescription())
                ->setTitle($title)
                ->setMetaDescription($title)
                ->setContent($this->getPropertyContent())
            );
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
                                $district $neighborhood, $metro, $title, $address,
                                $latitude, $longitude, $price, $priceType];
            */
            [
                $this->getReference('admin', User::class),
                $this->getReference('Sale', DealType::class),
                $this->getReference('Villa', Category::class),
                5,
                null,
                $this->getReference('Tampa', City::class),
                $this->getReference('South Tampa', District::class),
                $this->getReference('Culbreath Isles', Neighborhood::class),
                null,
                'Beautiful villa for sale in Tampa',
                '4935 New Providence Ave, Tampa, FL',
                '27.932255', '-82.533187', 1600, 'sq. foot',
            ],
            [
                $this->getReference('admin', User::class),
                $this->getReference('Rent', DealType::class),
                $this->getReference('Penthouse', Category::class),
                2,
                5,
                $this->getReference('Palm Beach', City::class),
                null,
                null,
                null,
                'Stylish two-level penthouse in Palm Beach',
                '101 Worth Ave, Palm Beach, FL 33480',
                '26.701320', '-80.033688', 2000, 'mo',
            ],
            [
                $this->getReference('admin', User::class),
                $this->getReference('Rent', DealType::class),
                $this->getReference('Apartment', Category::class),
                null,
                4,
                $this->getReference('Miami', City::class),
                null,
                $this->getReference('South Beach', Neighborhood::class),
                null,
                'Bright and Cheerful alcove studio',
                '1451 Ocean Dr, Miami Beach, FL 33139',
                '25.785107', '-80.129460', 200, 'day',
            ],
            [
                $this->getReference('admin', User::class),
                $this->getReference('Rent', DealType::class),
                $this->getReference('Apartment', Category::class),
                1,
                2,
                $this->getReference('Miami', City::class),
                null,
                $this->getReference('South Beach', Neighborhood::class),
                null,
                'Modern one-bedroom apartment in Miami',
                '1451 Ocean Dr, Miami Beach, FL 33139',
                '25.785107', '-80.129460', 250, 'day',
            ],
            [
                $this->getReference('admin', User::class),
                $this->getReference('Rent', DealType::class),
                $this->getReference('Apartment', Category::class),
                1,
                3,
                $this->getReference('Palm Beach', City::class),
                null,
                null,
                null,
                'Bright fully furnished 1-bedroom flat on the 2nd floor',
                '300 S Ocean Blvd, Palm Beach, FL',
                '26.705007', '-80.033574', 180, 'day',
            ],
            [
                $this->getReference('user', User::class),
                $this->getReference('Sale', DealType::class),
                $this->getReference('Apartment', Category::class),
                2,
                null,
                $this->getReference('Miami', City::class),
                null,
                $this->getReference('Downtown', Neighborhood::class),
                $this->getReference('Government Center', Metro::class),
                'Interesting two-bedroom apartment for sale',
                '111 NE 2nd Ave, Miami, FL 33132',
                '25.775565', '-80.190125', 190000, '',
            ],
            [
                $this->getReference('user', User::class),
                $this->getReference('Rent', DealType::class),
                $this->getReference('Apartment', Category::class),
                2,
                6,
                $this->getReference('Tampa', City::class),
                null,
                $this->getReference('Ballast Point', Neighborhood::class),
                null,
                'Furnished renovated 2-bedroom 2-bathroom flat',
                '5411 Bayshore Blvd, Tampa, FL 33611',
                '27.885095', '-82.486153', 2200, 'mo',
            ],
        ];
    }

    private function getPropertyContent(): string
    {
        return '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco
                laboris nisi ut aliquip ex ea commodo consequat.
                Duis aute irure dolor in reprehenderit in voluptate
                velit esse cillum dolore eu fugiat nulla pariatur.
                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui
                officia deserunt mollit anim id est laborum.</p>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                accusantium doloremque laudantium, totam rem aperiam,
                eaque ipsa quae ab illo inventore veritatis et quasi
                architecto beatae vitae dicta sunt explicabo.
                Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut
                odit aut fugit, sed quia consequuntur magni dolores eos qui
                ratione voluptatem sequi nesciunt. Neque porro quisquam est,
                qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit,
                sed quia non numquam eius modi tempora incidunt ut labore et dolore
                magnam aliquam quaerat voluptatem. Ut enim ad minima veniam,
                quis nostrum exercitationem ullam corporis suscipit laboriosam,
                nisi ut aliquid ex ea commodi consequatur.</p>';
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            CityFixtures::class,
            DealTypeFixtures::class,
            DistrictFixtures::class,
            FeatureFixtures::class,
            MetroFixtures::class,
            NeighborhoodFixtures::class,
            UserFixtures::class,
        ];
    }
}
