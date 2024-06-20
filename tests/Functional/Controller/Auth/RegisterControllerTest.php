<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Auth;

use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class RegisterControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const USER = [
        'PHP_AUTH_USER' => 'Tester',
        'PHP_AUTH_PW' => 'tester@test.org',
    ];

    // Make sure users are not allowed to create new accounts
    public function testRegistrationSuspended(): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, '/en/register');
        $this->assertResponseRedirects('/en/');
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', 'temporarily suspended');
    }

    public function testAdminUpdateSettings(): void
    {
        $client = $this->authAsAdmin($this);
        $crawler = $client->request(Request::METHOD_GET, '/en/admin/settings');
        $form = $crawler->selectButton('Save changes')->form([
            'main_settings[anyone_can_register]' => '1',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
    }

    public function testRegister(): void
    {
        $client = self::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/en/register');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.card-header', 'Register');
        $form = $crawler->selectButton('Create account')->form([
            'registration_form[username]' => self::USER['PHP_AUTH_USER'],
            'registration_form[email]' => self::USER['PHP_AUTH_PW'],
            'registration_form[password]' => self::USER['PHP_AUTH_PW'],
            'registration_form[agreeTerms]' => true,
        ]);
        $client->submit($form);
        $this->assertEmailCount(1);
        $this->assertResponseRedirects('/en/user/property');
        $user = $this->getUser($client, self::USER['PHP_AUTH_USER']);
        $this->assertSame(self::USER['PHP_AUTH_PW'], $user->getEmail());
        $this->assertFalse($user->isVerified());
    }

    // Try logging in as the newly created user
    public function testLogin(): void
    {
        $client = $this->authAsUser($this);
        $client->request(Request::METHOD_GET, '/en/user/profile');
        $this->assertSelectorTextContains('h3', 'My profile');
    }

    // Try to remove this user from the database
    public function testDeleteUser(): void
    {
        $client = $this->authAsAdmin($this);
        $user = $this->getUser($client, self::USER['PHP_AUTH_USER'])->getId();
        $crawler = $client->request(Request::METHOD_GET, '/en/admin/user');
        $client->submit($crawler->filter('#delete-form-'.$user)->form());
        $this->assertResponseRedirects('/en/admin/user');
        $this->resetSettings($client);
    }
}
