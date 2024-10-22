<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin\Settings;

use App\Entity\Property;
use App\Entity\Settings;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class MainSettingsControllerTest extends WebTestCase
{
    use WebTestHelper;

    public function testAdminEditSettings(): void
    {
        $client = $this->authAsAdmin($this);

        $title = $this->getRepository($client, Settings::class)
            ->findOneBy(['setting_name' => 'title'])->getSettingValue();

        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/en/admin/settings');

        $form = $crawler->selectButton('Save changes')->form([
            'main_settings[title]' => $title.' - Test title',
            'main_settings[fixed_top_navbar]' => '1',
            'main_settings[show_similar_properties]' => '1',
            'main_settings[items_per_page]' => '3',
            'main_settings[custom_footer_text]' => 'Edited text',
        ]);

        $client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }

    public function testPublicSettings(): void
    {
        $client = self::createClient();

        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/en/');

        $this->assertStringContainsString('Test title', $crawler->html());
        $this->assertStringContainsString('Edited text', $crawler->html());
        $this->assertCount(3, $crawler->filter('.property-box-img'));

        // Check if fixed top navigation bar is enabled
        $this->assertSelectorExists('.fixed-top');
        $this->assertSelectorExists('.body');

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'bright-and-cheerful-alcove-studio']);

        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET,
            \sprintf('/en/%s/%s/%d', $property->getCity()->getSlug(), $property->getSlug(), $property->getId())
        );
        // Check if similar properties are enabled
        $this->assertStringContainsString('Modern one-bedroom apartment in Miami', $crawler->filter('.card-title>a')
            ->text());
    }

    public function testChangedBackSettings(): void
    {
        $client = self::createClient();
        $this->resetSettings($client);

        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/en/');

        $this->assertStringNotContainsString('Test title', $crawler->html());
        $this->assertCount(6, $crawler->filter('.property-box-img'));

        // Check if fixed top navigation bar is disabled
        $this->assertSelectorNotExists('.fixed-top');
        $this->assertSelectorNotExists('.body');

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'bright-and-cheerful-alcove-studio']);

        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET,
            \sprintf('/en/%s/%s/%d', $property->getCity()->getSlug(), $property->getSlug(), $property->getId())
        );
        // Check if similar properties are disabled
        $this->assertStringNotContainsString('Similar Properties', $crawler->filter('h4')
            ->text());
    }
}
