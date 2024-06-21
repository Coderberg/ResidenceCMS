<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Currency;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CurrencyControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const CURRENCY = 'NEW';
    private const EDITED = 'EDT';

    /**
     * This test changes the database contents by creating a new Currency.
     */
    public function testAdminNewCurrency(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/currency/new');

        $form = $crawler->selectButton('Create currency')->form([
            'currency[currency_title]' => self::CURRENCY,
            'currency[code]' => self::CURRENCY,
        ]);
        $client->submit($form);

        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
        $currency = $this->getRepository($client, Currency::class)
            ->findOneBy([
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
        $client = $this->authAsAdmin($this);

        $currency = $this->getRepository($client, Currency::class)
            ->findOneBy([
                'code' => self::CURRENCY,
            ])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/currency/'.$currency.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'currency[currency_title]' => self::EDITED,
            'currency[code]' => self::EDITED,
        ]);

        $client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $editedCurrency = $this->getRepository($client, Currency::class)
            ->findOneBy([
                'id' => $currency,
            ]);

        $this->assertSame(self::EDITED, $editedCurrency->getCode());
    }

    /**
     * This test changes the database contents by deleting a test Currency.
     */
    public function testAdminDeleteCurrency(): void
    {
        $client = $this->authAsAdmin($this);

        $currency = $this->getRepository($client, Currency::class)
            ->findOneBy([
                'code' => self::EDITED,
            ])->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/currency');
        $client->submit($crawler->filter('#delete-form-'.$currency)->form());
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $this->assertNull($this->getRepository($client, Currency::class)->findOneBy([
            'code' => self::EDITED,
        ]));
    }
}
