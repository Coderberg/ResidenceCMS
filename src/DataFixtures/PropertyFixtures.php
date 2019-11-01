<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Property;
use App\Utils\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

final class PropertyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getPropertyData() as [$operation, $category, $locality, $area, $title,
                    $address, $latitude, $longitude, $price, $price_type, ]) {
            $property = new Property();
            $property->setAuthor($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
            $property->setOperation($operation);
            $property->setCategory($category);
            $property->setLocality($locality);
            $property->setArea($area);
            $property->setTitle($title);
            $property->setDescription($title);
            $property->setSlug(Slugger::slugify($title));
            $property->setContent($this->getPropertyContent());
            $property->setAddress($address);
            $property->setLatitude($latitude);
            $property->setLongitude($longitude);
            $property->setShowMap(true);
            $property->setPrice($price);
            $property->setPriceType($price_type);
            $property->setPublished(true);
            $property->setPublishedAt(new \DateTime('now'));
            $property->addFeature($this->getReference('Air conditioning'));
            $property->addFeature($this->getReference('Balcony'));
            $property->addFeature($this->getReference('Fire Alarm'));
            $property->addFeature($this->getReference('High Impact Doors'));
            $property->addFeature($this->getReference('Secure parking'));
            $manager->persist($property);
            $this->addReference(Slugger::slugify($title), $property);
        }
        $manager->flush();
    }

    private function getPropertyData(): array
    {
        return [
            /*
                $propertyData = [$operation_id, $category_id, $locality_id, $area,
                                $title, $address, $latitude, $longitude,
                                $price, $price_type];
            */
            [
                $this->getReference('Rent'),
                $this->getReference('Apartment'),
                $this->getReference('Miami'),
                $this->getReference('South Beach'),
                'Modern one-bedroom apartment in Miami',
                '1451 Ocean Dr, Miami Beach, FL 33139',
                '25.785107', '-80.129460', 250, 'day',
            ],
            [
                $this->getReference('Rent'),
                $this->getReference('Apartment'),
                $this->getReference('Miami'),
                $this->getReference('South Beach'),
                'Bright and Cheerful alcove studio',
                '1451 Ocean Dr, Miami Beach, FL 33139',
                '25.785107', '-80.129460', 200, 'day',
            ],
            [
                $this->getReference('Rent'),
                $this->getReference('Penthouse'),
                $this->getReference('Palm Beach'),
                null,
                'Stylish two-level penthouse in Palm Beach',
                '101 Worth Ave, Palm Beach, FL 33480',
                '26.701320', '-80.033688', 2000, 'mo',
            ],
            [
                $this->getReference('Rent'),
                $this->getReference('Apartment'),
                $this->getReference('Palm Beach'),
                null,
                'Bright fully furnished 1-bedroom flat on the 2nd floor',
                '300 S Ocean Blvd, Palm Beach, FL',
                '26.705007', '-80.033574', 180, 'day',
            ],
            [
                $this->getReference('Sale'),
                $this->getReference('Villa'),
                $this->getReference('Tampa'),
                $this->getReference('Culbreath Isles'),
                'Beautiful villa for sale in Tampa',
                '4935 New Providence Ave, Tampa, FL',
                '27.932255', '-82.533187', 1600, 'sq. foot',
            ],
            [
                $this->getReference('Rent'),
                $this->getReference('Apartment'),
                $this->getReference('Tampa'),
                $this->getReference('Ballast Point'),
                'Furnished renovated 2-bedroom 2-bathroom flat',
                '5411 Bayshore Blvd, Tampa, FL 33611',
                '27.885095', '-82.486153', 2200, 'mo',
            ],
            [
                $this->getReference('Sale'),
                $this->getReference('Apartment'),
                $this->getReference('Miami'),
                $this->getReference('Downtown'),
                'Interesting two-bedroom apartment for sale',
                '111 NE 2nd Ave, Miami, FL 33132',
                '25.775565', '-80.190125', 190000, '',
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

    public function getDependencies()
    {
        return [
            AreaFixtures::class,
            CategoryFixtures::class,
            FeatureFixtures::class,
            LocalityFixtures::class,
            OperationFixtures::class,
            UserFixtures::class,
        ];
    }
}
