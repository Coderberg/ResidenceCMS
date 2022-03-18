<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Auth;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegisterControllerTest extends WebTestCase
{
    private const USER = [
        'PHP_AUTH_USER' => 'Tester',
        'PHP_AUTH_PW' => 'tester@test.org',
    ];

    public function testRegister(): void
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/en/register');
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
        $this->assertResponseRedirects('/en/login');
        $user = $this->findUser($client);
        $this->assertSame(self::USER['PHP_AUTH_PW'], $user->getEmail());
        $this->assertFalse($user->isVerified());
    }

    // Try logging in as the newly created user
    public function testLogin(): void
    {
        $client = self::createClient([], self::USER);
        $client->request('GET', '/en/user/profile');
        $this->assertSelectorTextContains('h3', 'My profile');
    }

    // Try to remove this user from the database
    public function testDeleteUser(): void
    {
        $client = self::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
        $user = $this->findUser($client)->getId();
        $crawler = $client->request('GET', '/en/admin/user');
        $client->submit($crawler->filter('#delete-form-'.$user)->form());
        $this->assertResponseRedirects('/en/admin/user');
    }

    private function findUser($client): User
    {
        return $client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => self::USER['PHP_AUTH_USER']]);
    }
}
