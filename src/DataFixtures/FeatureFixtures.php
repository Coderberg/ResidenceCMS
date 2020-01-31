<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Feature;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class FeatureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getFeatureData() as [$name, $icon]) {
            $feature = new Feature();
            $feature->setName($name);
            $feature->setIcon($icon);
            $manager->persist($feature);
            $this->addReference($name, $feature);
        }
        $manager->flush();
    }

    private function getFeatureData(): array
    {
        return [
            ['Air conditioning', null],
            ['Balcony', null],
            ['Elevator', null],
            ['Fire Alarm', null],
            ['First Floor Entry', null],
            ['High Impact Doors', null],
            ['Patio', null],
            ['Secure parking', '<i class="fas fa-parking"></i>'],
        ];
    }
}
