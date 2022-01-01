<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CurrencyControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    private const CURRENCY = 'NEW';
    private const EDITED = 'EDT';

    /**
     * This test changes the database contents by creating a new Currency.
     */
    public function testAdminNewCurrency(): void
    {
        $client = static::createClient([], self::SERVER);

        $crawler = $client->request('GET', '/en/admin/currency/new');

        $form = $crawler->selectButton('Create currency')->form([
            'currency[currency_title]' => self::CURRENCY,
            'currency[code]' => self::CURRENCY,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $currency = $client->getContainer()->get('doctrine')
            ->getRepository(Currency::class)->findOneBy([
                'code' => self::CURRENCY,
            ]);

        $this->assertNotNull($currency);
        $this->assertSame(self::CURRENCY, $currency->getCode());
    }

    /**
     * This test changes the database contents by editing a Currency.
     */
    public function testAdminEditCurrency(): void
    {
        $client = static::createClient([], self::SERVER);

        $currency = $client->getContainer()->get('doctrine')
            ->getRepository(Currency::class)
            ->findOneBy([
                'code' => self::CURRENCY,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/currency/'.$currency.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'currency[currency_title]' => self::EDITED,
            'currency[code]' => self::EDITED,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedCurrency = $client->getContainer()->get('doctrine')
            ->getRepository(Currency::class)->findOneBy([
                'id' => $currency,
            ]);

        $this->assertSame(self::EDITED, $editedCurrency->getCode());
    }

    /**
     * This test changes the database contents by deleting a test Currency.
     */
    public function testAdminDeleteCurrency(): void
    {
        $client = static::createClient([], self::SERVER);

        $currency = $client->getContainer()->get('doctrine')
            ->getRepository(Currency::class)->findOneBy([
                'code' => self::EDITED,
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/currency');
        $client->submit($crawler->filter('#delete-form-'.$currency)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Currency::class)->findOneBy([
                'code' => self::EDITED,
            ]));
    }
}
