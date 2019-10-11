<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

final class SettingsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $setting = new Setting();
        $setting->setName('Site name');
        $setting->setTitle('Site Title');
        $setting->setDescription('Site Description');
        $setting->setItemsPerPage(6);
        $setting->setCurrency($this->getReference('USD'));
        $setting->setMapCenter('27.188534, -81.128735');
        $setting->setMapZoom(7);
        $manager->persist($setting);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CurrencyFixtures::class,
        ];
    }
}
