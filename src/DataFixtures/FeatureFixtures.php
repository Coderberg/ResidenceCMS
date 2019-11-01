<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Feature;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class FeatureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getFeatureData() as $name) {
            $feature = new Feature();
            $feature->setName($name);
            $manager->persist($feature);
            $this->addReference($name, $feature);
        }
        $manager->flush();
    }

    private function getFeatureData(): array
    {
        return [
            'Air conditioning',
            'Balcony',
            'Elevator',
            'Fire Alarm',
            'First Floor Entry',
            'High Impact Doors',
            'Patio',
            'Secure parking',
        ];
    }
}
