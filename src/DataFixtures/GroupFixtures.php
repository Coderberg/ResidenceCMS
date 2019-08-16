<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GroupFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
    }

    public function getDependencies()
    {
        return [
            SettingsFixtures::class,
            UserFixtures::class,
            PageFixtures::class,
            MenuFixtures::class,
            LocalityFixtures::class,
            CategoryFixtures::class,
            OperationFixtures::class,
            PropertyFixtures::class,
        ];
    }
}
