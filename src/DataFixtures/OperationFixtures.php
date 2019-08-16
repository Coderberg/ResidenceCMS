<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Operation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OperationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getOperationData() as [$slug, $name]) {
            $operation = new Operation();
            $operation->setName($name);
            $operation->setSlug($slug);
            $manager->persist($operation);
            $this->addReference($name, $operation);
        }
        $manager->flush();
    }

    private function getOperationData(): array
    {
        return [
            // $operationData = [$slug, $name];
            ['rent', 'Rent'],
            ['sale', 'Sale'],
        ];
    }
}
