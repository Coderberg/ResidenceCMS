<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PasswordControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const tempPassword = 'TestPassword123';

    public function testPasswordChangeWithoutToken(): void
    {
        $client = $this->authAsUser($this);
        $client->request('POST', '/en/user/password', []);
        $this->assertResponseStatusCodeSame(419);
        $this->isJson();
    }

    public function testInvalidPassword(): void
    {
        $client = $this->authAsUser($this);
        $token = $this->getToken($client);

        // Try too short password
        $client->request('POST', '/en/user/password', [
            'password1' => mb_substr(self::tempPassword, 0, -6),
            'password2' => self::tempPassword,
            'csrf_token' => $token,
        ]);
        $this->isJson();
        $this->assertResponseIsUnprocessable();

        // Try mismatched passwords
        $client->request('POST', '/en/user/password', [
            'password1' => self::tempPassword,
            'password2' => mb_substr(self::tempPassword, 0, -6),
            'csrf_token' => $token,
        ]);
        $this->isJson();
        $this->assertResponseIsUnprocessable();
    }

    public function testPasswordChange(): void
    {
        $client = $this->authAsUser($this);
        $token = $this->getToken($client);
        $this->assertNotEmpty($token);
        $client->request('POST', '/en/user/password', [
            'password1' => self::tempPassword,
            'password2' => self::tempPassword,
            'csrf_token' => $token,
        ]);
        $this->isJson();
        $this->assertResponseIsSuccessful();
    }

    private function getToken(KernelBrowser $client): string
    {
        $crawler = $client->request('GET', '/en/user/property');

        return $crawler->filter('[name="password_token"]')->attr('value');
    }

    public function testChangePasswordBack(): void
    {
        $this->restoreUserPassword($this);
        $this->assertResponseRedirects();
    }
}
