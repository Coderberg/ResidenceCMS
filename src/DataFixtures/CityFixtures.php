<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getCityData() as [$slug, $name]) {
            $city = new City();
            $city->setName($name);
            $city->setSlug($slug);
            $manager->persist($city);
            $this->addReference($name, $city);
        }
        $manager->flush();
    }

    private function getCityData(): array
    {
        return [
            // $cityData = [$slug, $name];
            ['miami', 'Miami'],
            ['palm-beach', 'Palm Beach'],
            ['tampa', 'Tampa'],
        ];
    }
}
