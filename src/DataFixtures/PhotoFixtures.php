<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Photo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class PhotoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getPhotoData() as [$property, $priority, $file]) {
            $photo = new Photo();
            $photo->setProperty($property);
            $photo->setSortOrder($priority);
            $photo->setPhoto($file);
            $manager->persist($photo);
        }
        $manager->flush();
    }

    private function getPhotoData(): array
    {
        return [
            [$this->getReference('bright-and-cheerful-alcove-studio'), 1, 'demo/1.jpeg'],
            [$this->getReference('bright-and-cheerful-alcove-studio'), 2, 'demo/2.jpeg'],
            [$this->getReference('modern-one-bedroom-apartment-in-miami'), 1, 'demo/3.jpeg'],
            [$this->getReference('modern-one-bedroom-apartment-in-miami'), 2, 'demo/4.jpeg'],
            [$this->getReference('stylish-two-level-penthouse-in-palm-beach'), 1, 'demo/5.jpeg'],
            [$this->getReference('stylish-two-level-penthouse-in-palm-beach'), 2, 'demo/6.jpeg'],
            [$this->getReference('bright-fully-furnished-1-bedroom-flat-on-the-2nd-floor'), 1, 'demo/7.jpeg'],
            [$this->getReference('bright-fully-furnished-1-bedroom-flat-on-the-2nd-floor'), 2, 'demo/2.jpeg'],
            [$this->getReference('beautiful-villa-for-sale-in-tampa'), 1, 'demo/8.jpeg'],
            [$this->getReference('beautiful-villa-for-sale-in-tampa'), 2, 'demo/9.jpeg'],
            [$this->getReference('beautiful-villa-for-sale-in-tampa'), 3, 'demo/4.jpeg'],
            [$this->getReference('furnished-renovated-2-bedroom-2-bathroom-flat'), 1, 'demo/10.jpeg'],
            [$this->getReference('furnished-renovated-2-bedroom-2-bathroom-flat'), 2, 'demo/6.jpeg'],
            [$this->getReference('interesting-two-bedroom-apartment-for-sale'), 1, 'demo/11.jpeg'],
            [$this->getReference('interesting-two-bedroom-apartment-for-sale'), 2, 'demo/12.jpeg'],
            [$this->getReference('interesting-two-bedroom-apartment-for-sale'), 3, 'demo/13.jpeg'],
        ];
    }

    public function getDependencies(): array
    {
        return [
            PropertyFixtures::class,
        ];
    }
}
