<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Locality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LocalityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getLocalityData() as [$slug, $name]) {
            $locality = new Locality();
            $locality->setName($name);
            $locality->setSlug($slug);
            $manager->persist($locality);
            $this->addReference($name, $locality);
        }
        $manager->flush();
    }

    private function getLocalityData(): array
    {
        return [
            // $localityData = [$slug, $name];
            ['miami', 'Miami'],
            ['palm-beach', 'Palm Beach'],
            ['tampa', 'Tampa'],
        ];
    }
}
