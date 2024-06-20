<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User\Security;

use App\Entity\User;
use App\Tests\Helper\WebTestHelper;
use Coderberg\GoogleAuthenticator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class GoogleAuthenticatorControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const ENDPOINT = '/en/user/google_authenticator_code';

    private const SECRET = 'KHVG3HBMNEHEKMBIK3RKPJTFJOE2WTESRUZDYQLPSTKUVBFWSVKA';

    public function testAuthCodeWithoutCsrfToken(): void
    {
        $client = $this->authAsUser($this);

        $client->request(Request::METHOD_GET, self::ENDPOINT, []);
        $this->assertResponseStatusCodeSame(419);
        $this->assertJson($client->getResponse()->getContent());

        $client->request(Request::METHOD_PUT, self::ENDPOINT, []);
        $this->assertResponseStatusCodeSame(419);
        $this->assertJson($client->getResponse()->getContent());

        $client->request(Request::METHOD_DELETE, self::ENDPOINT, []);
        $this->assertResponseStatusCodeSame(419);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetAuthCode(): void
    {
        $client = $this->authAsUser($this);
        $token = $this->getToken($client);
        $this->assertNotEmpty($token);
        $client->request(Request::METHOD_GET, self::ENDPOINT, [
            'csrf_token' => $token,
        ]);
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $this->assertTrue(mb_strlen($response->getContent()) > 2000 && mb_strlen($response->getContent()) < 3100);
        $this->assertContainsWords($response, ['secret', 'qr_code', 'data:image', 'png;base64']);
    }

    /**
     * @throws \Exception
     */
    public function testSetInvalidAuthCode(): void
    {
        // Send empty data
        $client = $this->authAsUser($this);
        $token = $this->getToken($client);
        $client->request(Request::METHOD_PUT, self::ENDPOINT, [
            'csrf_token' => $token,
        ]);
        $this->assertResponseIsUnprocessable();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $this->assertContainsWords($response, ['Cannot enable Google Authenticator']);

        // Set wrong data
        $client->request(Request::METHOD_PUT, self::ENDPOINT, [
            'secret' => self::SECRET,
            'authentication_code' => random_int(100000, 999999),
            'csrf_token' => $token,
        ]);
        $this->assertResponseIsUnprocessable();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $this->assertContainsWords($response, ['The Google Authenticator code is incorrect or has expired']);
    }

    public function testSetAuthCode(): void
    {
        $client = $this->authAsUser($this);
        $token = $this->getToken($client);

        $user = $this->getUser($client, 'user');

        $user->setGoogleAuthenticatorSecret(self::SECRET);

        $ga = new GoogleAuthenticator();
        $oneTimePassword = $ga->getCode(self::SECRET);

        $client->request(Request::METHOD_PUT, self::ENDPOINT, [
            'secret' => self::SECRET,
            'authentication_code' => $oneTimePassword,
            'csrf_token' => $token,
        ]);

        $this->assertResponseIsSuccessful();

        $updatedUser = $this->getRepository($client, User::class)->findOneBy([
            'username' => 'user',
        ]);

        $this->assertTrue($updatedUser->isGoogleAuthenticatorEnabled());
    }

    public function testDeleteAuthCode(): void
    {
        $client = $this->authAsUser($this);
        $token = $this->getToken($client);

        $client->request(Request::METHOD_DELETE, self::ENDPOINT, [
            'csrf_token' => $token,
        ]);

        $this->assertResponseIsSuccessful();

        $updatedUser = $this->getRepository($client, User::class)->findOneBy([
            'username' => 'user',
        ]);

        $this->assertFalse($updatedUser->isGoogleAuthenticatorEnabled());
    }

    private function getToken(KernelBrowser $client): string
    {
        $crawler = $client->request(Request::METHOD_GET, '/en/user/security');

        return $crawler->filter('[name="auth_token"]')->attr('value');
    }
}
