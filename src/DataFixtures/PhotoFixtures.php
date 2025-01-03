<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Photo;
use App\Entity\Property;
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
            [$this->getReference('bright-and-cheerful-alcove-studio', Property::class), 1, 'demo/1.jpeg'],
            [$this->getReference('bright-and-cheerful-alcove-studio', Property::class), 2, 'demo/2.jpeg'],
            [$this->getReference('modern-one-bedroom-apartment-in-miami', Property::class), 1, 'demo/3.jpeg'],
            [$this->getReference('modern-one-bedroom-apartment-in-miami', Property::class), 2, 'demo/4.jpeg'],
            [$this->getReference('stylish-two-level-penthouse-in-palm-beach', Property::class), 1, 'demo/5.jpeg'],
            [$this->getReference('stylish-two-level-penthouse-in-palm-beach', Property::class), 2, 'demo/6.jpeg'],
            [$this->getReference('bright-fully-furnished-1-bedroom-flat-on-the-2nd-floor', Property::class), 1, 'demo/7.jpeg'],
            [$this->getReference('bright-fully-furnished-1-bedroom-flat-on-the-2nd-floor', Property::class), 2, 'demo/2.jpeg'],
            [$this->getReference('beautiful-villa-for-sale-in-tampa', Property::class), 1, 'demo/8.jpeg'],
            [$this->getReference('beautiful-villa-for-sale-in-tampa', Property::class), 2, 'demo/9.jpeg'],
            [$this->getReference('beautiful-villa-for-sale-in-tampa', Property::class), 3, 'demo/4.jpeg'],
            [$this->getReference('furnished-renovated-2-bedroom-2-bathroom-flat', Property::class), 1, 'demo/10.jpeg'],
            [$this->getReference('furnished-renovated-2-bedroom-2-bathroom-flat', Property::class), 2, 'demo/6.jpeg'],
            [$this->getReference('interesting-two-bedroom-apartment-for-sale', Property::class), 1, 'demo/11.jpeg'],
            [$this->getReference('interesting-two-bedroom-apartment-for-sale', Property::class), 2, 'demo/12.jpeg'],
            [$this->getReference('interesting-two-bedroom-apartment-for-sale', Property::class), 3, 'demo/13.jpeg'],
        ];
    }

    public function getDependencies(): array
    {
        return [
            PropertyFixtures::class,
        ];
    }
}
