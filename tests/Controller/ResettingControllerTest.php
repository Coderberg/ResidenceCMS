<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ResettingControllerTest extends WebTestCase
{
    public function testPasswordReset()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/password/reset');

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
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'We have e-mailed your password reset link!');
    }

    public function testChangePassword()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)->findOneBy(['username' => 'admin']);

        $crawler = $client->request('GET', sprintf('/en/password/reset/%s', $user->getConfirmationToken()));
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

    public function testLogin()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);

        $client->request('GET', '/en/admin');
        $this->assertSelectorTextContains('.navbar-brand', 'Dashboard');

        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)->findOneBy(['username' => 'admin']);

        $this->assertNull($user->getConfirmationToken());
        $this->assertNull($user->getPasswordRequestedAt());
    }
}
