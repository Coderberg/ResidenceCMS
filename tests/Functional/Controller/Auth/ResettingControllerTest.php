<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Auth;

use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class ResettingControllerTest extends WebTestCase
{
    use WebTestHelper;

    public function testPasswordReset(): void
    {
        $client = self::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/en/password/reset');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.card-header', 'Reset Password');

        $form = $crawler->selectButton('Send Password Reset Link')->form([
            'user_email[email]' => 'test@test.com',
        ]);

        $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.form-error-message', 'We can\'t find a user with that e-mail address.');

        $form = $crawler->selectButton('Send Password Reset Link')->form([
            'user_email[email]' => 'admin@admin.com',
        ]);

        $client->submit($form);
        $this->assertEmailCount(1);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'We have e-mailed your password reset link!');
    }

    public function testChangePassword(): void
    {
        $client = self::createClient();
        $client->followRedirects();

        $user = $this->getUser($client, 'admin');

        $crawler = $client->request(Request::METHOD_GET, \sprintf('/en/password/reset/%s', $user->getConfirmationToken()));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.card-header', 'Set a new password');

        $form = $crawler->selectButton('Continue')->form([
            'password[password]' => 'admin',
            'password[password_confirmation]' => 'admin',
        ]);
        $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'Password has been changed successfully');
    }

    public function testLogin(): void
    {
        $client = $this->authAsAdmin($this);

        $client->request(Request::METHOD_GET, '/en/admin');
        $this->assertSelectorTextContains('.navbar-brand', 'Dashboard');

        $user = $this->getUser($client, 'admin');

        $this->assertNull($user->getConfirmationToken());
        $this->assertNull($user->getPasswordRequestedAt());
    }
}
