<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CurrencyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getCurrencyData() as [$currencyTitle, $code, $symbolLeft, $symbolRight]) {
            $currency = new Currency();
            $currency->setCurrencyTitle($currencyTitle);
            $currency->setCode($code);
            $currency->setSymbolLeft($symbolLeft);
            $currency->setSymbolRight($symbolRight);

            $manager->persist($currency);
            $this->addReference($code, $currency);
        }
        $manager->flush();
    }

    private function getCurrencyData(): array
    {
        return [
            ['US Dollar', 'USD', '$', ''],
            ['Euro', 'EUR', '', '€'],
            ['Pound Sterling', 'GBP', '£', ''],
            ['Hong Kong Dollar', 'HKD', 'HK$', ''],
            ['Russian Ruble', 'RUB', '₽', ''],
            ['Belarusian ruble', 'BYN', '', 'Br'],
        ];
    }
}
