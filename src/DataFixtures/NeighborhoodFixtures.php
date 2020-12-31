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
           [$this->getReference('Варна'), 'бул. Владислав Варненчик', 'bul-vladislav-varnenchik'],
           [$this->getReference('Варна'), 'Владиславово', 'vladislavovo'],
           [$this->getReference('Варна'), 'Възраждане 1', 'vazdrazhdane-1'],
           [$this->getReference('Варна'), 'Възраждане 2', 'vazdrazhdane-2'],
           [$this->getReference('Варна'), 'Възраждане 3', 'vazdrazhdane-3'],
           [$this->getReference('Варна'), 'Възраждане 4', 'vazdrazhdane-4'],
           [$this->getReference('Варна'), 'Зимно кино тракия', 'zimno-kino-trakiya'],
           [$this->getReference('Варна'), 'Лятно кино тракия', 'lyatno-kino-trakiya'],
           [$this->getReference('Варна'), 'Кайсиева градина', 'kaysieva-gradina'],
           [$this->getReference('Варна'), 'Младост 1', 'mladost-1'],
           [$this->getReference('Варна'), 'Младост 2', 'mladost-2'],
           [$this->getReference('Варна'), 'м-т Балъм дере', 'm-t-balam-dere'],
           [$this->getReference('Варна'), 'м-т Ментеше', 'm-t-menteshe'],
           [$this->getReference('Варна'), 'м-т Боровец - Юг', 'm-t-borovetsh-yug'],
           [$this->getReference('Варна'), 'м-т Боровец - Север', 'm-t-borovets-sever'],
           [$this->getReference('Варна'), 'Галата', 'galata'],
           [$this->getReference('Варна'), 'Трошево', 'troshevo'],
        ];
    }

    public function getDependencies()
    {
        return [
            CityFixtures::class,
        ];
    }
}
