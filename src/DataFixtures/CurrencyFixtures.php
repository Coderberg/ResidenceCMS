<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class CurrencyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getCurrencyData() as [$currencyTitle, $code, $symbolLeft, $symbolRight]) {
            $category = new Currency();
            $category->setCurrencyTitle($currencyTitle);
            $category->setCode($code);
            $category->setSymbolLeft($symbolLeft);
            $category->setSymbolRight($symbolRight);

            $manager->persist($category);
            $this->addReference($code, $category);
        }
        $manager->flush();
    }

    private function getCurrencyData(): array
    {
        return [
            // $currencyData = [$currencyTitle, $code, $symbolLeft, $symbolRight];
            ['US Dollar', 'USD', '$', ''],
            ['Euro', 'EUR', '', '€'],
            ['Pound Sterling', 'GBP', '£', ''],
            ['Hong Kong Dollar', 'HKD', 'HK$', ''],
            ['Russian Ruble', 'RUB', '₽', ''],
            ['Belarusian ruble', 'BYN', '', 'Br'],
        ];
    }
}
