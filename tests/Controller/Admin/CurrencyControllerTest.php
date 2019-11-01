<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CurrencyControllerTest extends WebTestCase
{
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    private const CURRENCY = 'NEW';
    private const EDITED = 'EDT';

    /**
     * This test changes the database contents by creating a new Currency.
     */
    public function testAdminNewCurrency()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $crawler = $client->request('GET', '/admin/currency/new');

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
    public function testAdminEditCurrency()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $currency = $client->getContainer()->get('doctrine')
            ->getRepository(Currency::class)
            ->findOneBy([
                'code' => self::CURRENCY,
            ])->getId();

        $crawler = $client->request('GET', '/admin/currency/'.$currency.'/edit');

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
    public function testAdminDeleteCurrency()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $currency = $client->getContainer()->get('doctrine')
            ->getRepository(Currency::class)->findOneBy([
                'code' => self::EDITED,
            ])->getId();

        $crawler = $client->request('GET', '/admin/currency');
        $client->submit($crawler->filter('#delete-form-'.$currency)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Currency::class)->findOneBy([
                'code' => self::EDITED,
            ]));
    }
}
