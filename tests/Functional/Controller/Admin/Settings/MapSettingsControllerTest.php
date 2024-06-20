<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin\Settings;

use App\Entity\Settings;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, self::MAP_SETTINGS_PAGE);

        $form = $crawler->selectButton('Save changes')->form([
            'map_settings[ymaps_key]' => self::TEST_KEY,
        ]);

        $client->submit($form);
        $this->assertResponseRedirects(self::MAP_SETTINGS_PAGE);
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Updated successfully');
        $this->resetSettings($client);
    }
}
