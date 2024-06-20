<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User\Security;

use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class PasswordControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const TEMP_PASSWORD = 'TestPassword123';
    private const ENDPOINT = '/en/user/password';

    public function testPasswordChangeWithoutToken(): void
    {
        $client = $this->authAsUser($this);
        $client->request(Request::METHOD_POST, self::ENDPOINT, []);
        $this->assertResponseStatusCodeSame(419);
        $this->isJson();
    }

    public function testInvalidPassword(): void
    {
        $client = $this->authAsUser($this);
        $token = $this->getToken($client);

        // Try too short password
        $client->request(Request::METHOD_POST, self::ENDPOINT, [
            'password1' => mb_substr(self::TEMP_PASSWORD, 0, -6),
            'password2' => self::TEMP_PASSWORD,
            'csrf_token' => $token,
        ]);
        $this->isJson();
        $this->assertResponseIsUnprocessable();

        // Try mismatched passwords
        $client->request(Request::METHOD_POST, self::ENDPOINT, [
            'password1' => self::TEMP_PASSWORD,
            'password2' => mb_substr(self::TEMP_PASSWORD, 0, -6),
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
        $client->request(Request::METHOD_POST, self::ENDPOINT, [
            'password1' => self::TEMP_PASSWORD,
            'password2' => self::TEMP_PASSWORD,
            'csrf_token' => $token,
        ]);
        $this->isJson();
        $this->assertResponseIsSuccessful();
    }

    private function getToken(KernelBrowser $client): string
    {
        $crawler = $client->request(Request::METHOD_GET, '/en/user/security');

        return $crawler->filter('[name="password_token"]')->attr('value');
    }

    public function testChangePasswordBack(): void
    {
        $this->restoreUserPassword($this);
        $this->assertResponseRedirects();
    }
}
