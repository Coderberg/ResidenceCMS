<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Settings;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class SettingsControllerTest extends WebTestCase
{
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    public function testAdminEditSettings()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $title = $client->getContainer()->get('doctrine')
            ->getRepository(Settings::class)->findOneBy(['setting_name' => 'title'])->getSettingValue();

        $crawler = $client->request('GET', '/admin/setting');

        $form = $crawler->selectButton('Save changes')->form([
            'settings[title]' => $title.' - Test title',
            'settings[items_per_page]' => '3',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testPublicSettings()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertContains('Test title', $crawler->html());
        $this->assertCount(3, $crawler->filter('.property-box-img'));
    }

    public function testAdminReturnSettings()
    {
        $client = static::createClient([], [
                'PHP_AUTH_USER' => self::PHP_AUTH_USER,
                'PHP_AUTH_PW' => self::PHP_AUTH_PW,
            ]);

        $title = $client->getContainer()->get('doctrine')
                ->getRepository(Settings::class)->findOneBy(['setting_name' => 'title'])->getSettingValue();

        $crawler = $client->request('GET', '/admin/setting');

        $form = $crawler->selectButton('Save changes')->form([
                'settings[title]' => mb_substr($title, 0, -13),
                'settings[items_per_page]' => '6',
            ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testReturnedSettings()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertNotContains('Test title', $crawler->html());
        $this->assertCount(6, $crawler->filter('.property-box-img'));
    }
}
