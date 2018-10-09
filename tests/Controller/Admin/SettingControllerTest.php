<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Setting;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class SettingControllerTest extends WebTestCase
{
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    public function testAdminEditSettings()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW
        ]);

        $setting = $client->getContainer()
            ->get('doctrine')->getRepository(Setting::class)->findAll()[0];

        $crawler = $client->request('GET', '/admin/homepage_setting');

        $form = $crawler->selectButton('Save changes')->form([
            'setting[homepage_title]' => $setting->getHomepageTitle() . ' - Test',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedSetting = $client->getContainer()->get('doctrine')
            ->getRepository(Setting::class)->findAll()[0];

        $this->assertContains('Test', $editedSetting->getHomepageTitle());
    }

    public function testAdminReturnSettings()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW
        ]);

        $setting = $client->getContainer()
            ->get('doctrine')->getRepository(Setting::class)->findAll()[0];

        $crawler = $client->request('GET', '/admin/homepage_setting');

        $form = $crawler->selectButton('Save changes')->form([
            'setting[homepage_title]' => mb_substr($setting->getHomepageTitle(), 0, -7),
        ]);

        $client->submit($form);

        $editedSetting = $client->getContainer()->get('doctrine')
            ->getRepository(Setting::class)->findAll()[0];

        $this->assertNotContains('Test', $editedSetting->getHomepageTitle());
    }

}
