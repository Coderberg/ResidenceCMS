<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getCityData() as [$slug, $name, $title]) {
            $city = new City();
            $city->setName($name);
            $city->setSlug($slug);
            $city->setTitle($title);
            $city->setMetaTitle($title);
            $manager->persist($city);
            $this->addReference($name, $city);
        }
        $manager->flush();
    }

    private function getCityData(): array
    {
        return [
            // $cityData = [$slug, $name, $title];
            ['miami', 'Miami', 'Miami Luxury Real Estate'],
            ['palm-beach', 'Palm Beach', 'West Palm Beach, FL Apartments'],
            ['tampa', 'Tampa', 'Tampa Real Estate'],
        ];
    }
}
