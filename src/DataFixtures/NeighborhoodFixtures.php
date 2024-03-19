<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Neighborhood;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class NeighborhoodFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getNeighborhoodData() as [$city, $name, $slug]) {
            $neighborhood = new Neighborhood();
            $neighborhood->setCity($city);
            $neighborhood->setName($name);
            $neighborhood->setSlug($slug);
            $manager->persist($neighborhood);
            $this->addReference($name, $neighborhood);
        }
        $manager->flush();
    }

    private function getNeighborhoodData(): array
    {
        return [
            // $neighborhoodData = [$city, $name, $slug];
            [$this->getReference('Miami'), 'South Beach', 'south-beach'],
            [$this->getReference('Miami'), 'Downtown', 'downtown'],
            [$this->getReference('Tampa'), 'Ballast Point', 'ballast-point'],
            [$this->getReference('Tampa'), 'Culbreath Isles', 'culbreath-isles'],
        ];
    }

    public function getDependencies(): array
    {
        return [
            CityFixtures::class,
        ];
    }
}
