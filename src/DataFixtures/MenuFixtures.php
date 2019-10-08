<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Menu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class MenuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getMenuData() as [$title, $url]) {
            $menu = new Menu();
            $menu->setTitle($title);
            $menu->setUrl($url);
            $manager->persist($menu);
            $this->addReference($title, $menu);
        }
        $manager->flush();
    }

    private function getMenuData(): array
    {
        return [
            // $menuData = [$title, $url];
            ['Homepage', '/'],
            ['About Us', '/info/about-us'],
            ['Contact', '/info/contact'],
        ];
    }
}
