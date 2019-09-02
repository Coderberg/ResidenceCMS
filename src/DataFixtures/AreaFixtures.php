<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Area;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

final class AreaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getAreaData() as [$locality, $name, $slug]) {
            $area = new Area();
            $area->setLocality($locality);
            $area->setName($name);
            $area->setSlug($slug);
            $manager->persist($area);
            $this->addReference($name, $area);
        }
        $manager->flush();
    }

    private function getAreaData(): array
    {
        return [
            // $areaData = [$locality, $name, $slug];
           [$this->getReference('Miami'), 'South Beach', 'south-beach'],
           [$this->getReference('Miami'), 'Downtown', 'downtown'],
           [$this->getReference('Tampa'), 'Ballast Point', 'ballast-point'],
           [$this->getReference('Tampa'), 'Culbreath Isles', 'culbreath-isles'],
        ];
    }

    public function getDependencies()
    {
        return [
            LocalityFixtures::class,
        ];
    }
}
