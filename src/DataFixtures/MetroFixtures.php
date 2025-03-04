<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Metro;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class MetroFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getMetroData() as [$city, $name, $slug]) {
            $metro = new Metro();
            $metro->setCity($city);
            $metro->setName($name);
            $metro->setSlug($slug);
            $manager->persist($metro);
            $this->addReference($name, $metro);
        }
        $manager->flush();
    }

    private function getMetroData(): array
    {
        return [
            [$this->getReference('Miami', City::class), 'Government Center', 'government-center'],
            [$this->getReference('Miami', City::class), 'Allapattah', 'allapattah'],
            [$this->getReference('Miami', City::class), 'Brickell', 'brickell'],
            [$this->getReference('Miami', City::class), 'Culmer', 'culmer'],
        ];
    }

    public function getDependencies(): array
    {
        return [
            CityFixtures::class,
        ];
    }
}
