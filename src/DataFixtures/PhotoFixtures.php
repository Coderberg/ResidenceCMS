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
            // $photoData = [$property, $priority, $file];
            [$this->getReference('krasiva-vila-za-prodazhba-v-m-t-borovets-yug'), 1, 'demo/8.jpeg'],
        ];
    }

    public function getDependencies()
    {
        return [
            PropertyFixtures::class,
        ];
    }
}
