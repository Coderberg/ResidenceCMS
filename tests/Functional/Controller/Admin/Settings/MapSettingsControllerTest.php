<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin\Settings;

use App\Entity\Settings;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class MapSettingsControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const MAP_SETTINGS_PAGE = '/en/admin/settings/map';
    private const TEST_KEY = 'Yandex Maps Key';

    public function testAdminEditSettings(): void
    {
        $client = $this->authAsAdmin($this);

        $initialKey = $this->getRepository($client, Settings::class)
            ->findOneBy(['setting_name' => 'ymaps_key'])->getSettingValue();

        $this->assertEmpty($initialKey);

        $crawler = $client->request('GET', self::MAP_SETTINGS_PAGE);

        $form = $crawler->selectButton('Save changes')->form([
            'map_settings[ymaps_key]' => self::TEST_KEY,
        ]);

        $client->submit($form);
        $this->assertResponseRedirects(self::MAP_SETTINGS_PAGE);
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Updated successfully');
    }

    public function testChangeBackSettings(): void
    {
        $client = $this->authAsAdmin($this);

        $initialKey = $this->getRepository($client, Settings::class)
            ->findOneBy(['setting_name' => 'ymaps_key'])->getSettingValue();

        $this->assertSame(self::TEST_KEY, $initialKey);

        $crawler = $client->request('GET', self::MAP_SETTINGS_PAGE);

        $form = $crawler->selectButton('Save changes')->form([
            'map_settings[ymaps_key]' => '',
            'map_settings[map_center]' => '27.188534, -81.128735',
            'map_settings[map_zoom]' => '7',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Updated successfully');
    }
}
