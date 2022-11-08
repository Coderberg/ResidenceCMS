<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\DealType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class DealTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getDealTypeData() as [$slug, $name]) {
            $dealType = new DealType();
            $dealType->setName($name);
            $dealType->setSlug($slug);
            $manager->persist($dealType);
            $this->addReference($name, $dealType);
        }
        $manager->flush();
    }

    private function getDealTypeData(): array
    {
        return [
            ['rent', 'Rent'],
            ['sale', 'Sale'],
        ];
    }
}
