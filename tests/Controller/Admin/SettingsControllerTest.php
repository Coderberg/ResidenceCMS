<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Property;
use App\Entity\Settings;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class SettingsControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    public function testAdminEditSettings()
    {
        $client = static::createClient([], self::SERVER);

        $title = $client->getContainer()->get('doctrine')
            ->getRepository(Settings::class)->findOneBy(['setting_name' => 'title'])->getSettingValue();

        $crawler = $client->request('GET', '/admin/settings');

        $form = $crawler->selectButton('Save changes')->form([
            'settings[title]' => $title.' - Test title',
            'settings[fixed_top_navbar]' => '1',
            'settings[show_similar_properties]' => '1',
            'settings[items_per_page]' => '3',
            'settings[custom_footer_text]' => 'Edited text',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testPublicSettings()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertStringContainsString('Test title', $crawler->html());
        $this->assertStringContainsString('Edited text', $crawler->html());
        $this->assertCount(3, $crawler->filter('.property-box-img'));

        // Check if fixed top navigation bar is enabled
        $this->assertSelectorExists('.fixed-top');
        $this->assertSelectorExists('.body');

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'bright-and-cheerful-alcove-studio']);

        $crawler = $client->request('GET',
            sprintf('/%s/%s/%d', $property->getCity()->getSlug(), $property->getSlug(), $property->getId())
        );
        // Check if similar properties are enabled
        $this->assertStringContainsString('Modern one-bedroom apartment in Miami', $crawler->filter('.card-title>a')
            ->text());
    }

    public function testChangeBackSettings()
    {
        $client = static::createClient([], self::SERVER);

        $title = $client->getContainer()->get('doctrine')
                ->getRepository(Settings::class)->findOneBy(['setting_name' => 'title'])->getSettingValue();

        $crawler = $client->request('GET', '/admin/settings');

        $form = $crawler->selectButton('Save changes')->form([
                'settings[title]' => mb_substr($title, 0, -13),
                'settings[custom_footer_text]' => 'All Rights Reserved.',
                'settings[fixed_top_navbar]' => '0',
                'settings[show_similar_properties]' => '0',
                'settings[items_per_page]' => '6',
            ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testChangedBackSettings()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertStringNotContainsString('Test title', $crawler->html());
        $this->assertCount(6, $crawler->filter('.property-box-img'));

        // Check if fixed top navigation bar is disabled
        $this->assertSelectorNotExists('.fixed-top');
        $this->assertSelectorNotExists('.body');

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'bright-and-cheerful-alcove-studio']);

        $crawler = $client->request('GET',
            sprintf('/%s/%s/%d', $property->getCity()->getSlug(), $property->getSlug(), $property->getId())
        );
        // Check if similar properties are disabled
        $this->assertStringNotContainsString('Similar Properties', $crawler->filter('h4')
            ->text());
    }

    public function testUploadHeaderImage()
    {
        $client = static::createClient([], self::SERVER);

        $crawler = $client->request('GET', '/admin/setting/header');
        $this->assertSelectorTextContains('html', 'Header settings');

        $image = __DIR__.'/../../../public/uploads/images/full/demo/1.jpeg';

        $form = $crawler->filter('.js-photo-dropzone')->form();
        $form['file']->upload($image);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testDeleteHeaderImage()
    {
        $client = static::createClient([], self::SERVER);

        $crawler = $client->request('GET', '/admin/setting/header');
        $this->assertSelectorExists('.remove');
        $client->submit($crawler->filter('#delete-form')->form());

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testDeletedHeaderImage()
    {
        $client = static::createClient();

        $this->assertEmpty($client->getContainer()->get('doctrine')
            ->getRepository(Settings::class)->findOneBy([
                'setting_name' => 'header_image',
            ])->getSettingValue());
    }
}
