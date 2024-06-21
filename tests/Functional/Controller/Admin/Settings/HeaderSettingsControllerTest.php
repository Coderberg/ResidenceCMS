<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin\Settings;

use App\Entity\Settings;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class HeaderSettingsControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const SETTINGS_PAGE_PATH = '/en/admin/settings/header';

    public function testUploadHeaderImage(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, self::SETTINGS_PAGE_PATH);
        $this->assertSelectorTextContains('html', 'Header settings');

        $image = __DIR__.'/../../../../../public/uploads/images/full/demo/1.jpeg';

        $form = $crawler->filter('.js-photo-dropzone')->last()->form();
        $form['file']->upload($image);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testUploadLogoImage(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, self::SETTINGS_PAGE_PATH);
        $this->assertSelectorTextContains('html', 'Header settings');

        $image = __DIR__.'/../../../../../public/images/logo-square.png';

        $form = $crawler->filter('.js-photo-dropzone')->first()->form();
        $form['file']->upload($image);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testDeleteHeaderImage(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, self::SETTINGS_PAGE_PATH);
        $this->assertSelectorExists('.remove-header_image');
        $client->submit($crawler->filter('#delete-form-header_image')->form());

        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }

    public function testDeleteLogoImage(): void
    {
        $client = $this->authAsAdmin($this);

        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, self::SETTINGS_PAGE_PATH);
        $this->assertSelectorExists('.remove-logo_image');
        $client->submit($crawler->filter('#delete-form-logo_image')->form());

        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
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
