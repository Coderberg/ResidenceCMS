<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Property;
use App\Entity\Settings;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class SettingsControllerTest extends WebTestCase
{
    use WebTestHelper;

    public function testAdminEditSettings(): void
    {
        $client = $this->authAsAdmin($this);

        $title = $this->getRepository($client, Settings::class)
            ->findOneBy(['setting_name' => 'title'])->getSettingValue();

        $crawler = $client->request('GET', '/en/admin/settings');

        $form = $crawler->selectButton('Save changes')->form([
            'main_settings[title]' => $title.' - Test title',
            'main_settings[fixed_top_navbar]' => '1',
            'main_settings[show_similar_properties]' => '1',
            'main_settings[items_per_page]' => '3',
            'main_settings[custom_footer_text]' => 'Edited text',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testPublicSettings(): void
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/en/');

        $this->assertStringContainsString('Test title', $crawler->html());
        $this->assertStringContainsString('Edited text', $crawler->html());
        $this->assertCount(3, $crawler->filter('.property-box-img'));

        // Check if fixed top navigation bar is enabled
        $this->assertSelectorExists('.fixed-top');
        $this->assertSelectorExists('.body');

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'bright-and-cheerful-alcove-studio']);

        $crawler = $client->request('GET',
            sprintf('/en/%s/%s/%d', $property->getCity()->getSlug(), $property->getSlug(), $property->getId())
        );
        // Check if similar properties are enabled
        $this->assertStringContainsString('Modern one-bedroom apartment in Miami', $crawler->filter('.card-title>a')
            ->text());
    }

    public function testChangeBackSettings(): void
    {
        $client = $this->authAsAdmin($this);

        $title = $this->getRepository($client, Settings::class)
            ->findOneBy(['setting_name' => 'title'])->getSettingValue();

        $crawler = $client->request('GET', '/en/admin/settings');

        $form = $crawler->selectButton('Save changes')->form([
            'main_settings[title]' => mb_substr($title, 0, -13),
            'main_settings[custom_footer_text]' => 'All Rights Reserved.',
            'main_settings[fixed_top_navbar]' => '0',
            'main_settings[show_similar_properties]' => '0',
            'main_settings[items_per_page]' => '6',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testChangedBackSettings(): void
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/en/');

        $this->assertStringNotContainsString('Test title', $crawler->html());
        $this->assertCount(6, $crawler->filter('.property-box-img'));

        // Check if fixed top navigation bar is disabled
        $this->assertSelectorNotExists('.fixed-top');
        $this->assertSelectorNotExists('.body');

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'bright-and-cheerful-alcove-studio']);

        $crawler = $client->request('GET',
            sprintf('/en/%s/%s/%d', $property->getCity()->getSlug(), $property->getSlug(), $property->getId())
        );
        // Check if similar properties are disabled
        $this->assertStringNotContainsString('Similar Properties', $crawler->filter('h4')
            ->text());
    }

    public function testUploadHeaderImage(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request('GET', '/en/admin/settings/header');
        $this->assertSelectorTextContains('html', 'Header settings');

        $image = __DIR__.'/../../../../public/uploads/images/full/demo/1.jpeg';

        $form = $crawler->filter('.js-photo-dropzone')->last()->form();
        $form['file']->upload($image);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testUploadLogoImage(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request('GET', '/en/admin/settings/header');
        $this->assertSelectorTextContains('html', 'Header settings');

        $image = __DIR__.'/../../../../public/images/logo-square.png';

        $form = $crawler->filter('.js-photo-dropzone')->first()->form();
        $form['file']->upload($image);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testDeleteHeaderImage(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request('GET', '/en/admin/settings/header');
        $this->assertSelectorExists('.remove-header_image');
        $client->submit($crawler->filter('#delete-form-header_image')->form());

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testDeleteLogoImage(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request('GET', '/en/admin/settings/header');
        $this->assertSelectorExists('.remove-logo_image');
        $client->submit($crawler->filter('#delete-form-logo_image')->form());

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testDeletedHeaderImage(): void
    {
        $client = self::createClient();

        $this->assertEmpty($this->getRepository($client, Settings::class)
            ->findOneBy([
                'setting_name' => 'header_image',
            ])->getSettingValue());
    }

    public function testDeletedLogoImage(): void
    {
        $client = self::createClient();

        $this->assertEmpty($this->getRepository($client, Settings::class)->findOneBy([
                'setting_name' => 'logo_image',
            ])->getSettingValue());
    }
}
