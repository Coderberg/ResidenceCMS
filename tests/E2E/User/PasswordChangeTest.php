<?php

declare(strict_types=1);

namespace App\Tests\E2E\User;

use App\Entity\User;
use App\Tests\E2E\AuthHelper;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Symfony\Component\Panther\PantherTestCase;

final class PasswordChangeTest extends PantherTestCase
{
    use AuthHelper;

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function testPasswordChange(): void
    {
        // Log In as a User
        $client = self::createPantherClient();
        $this->login($client, 'user', 'user');
        $client->waitFor('[data-target="#changePassword"]');

        // Open the modal window
        $client->clickLink('Change Password');
        $crawler = $client->waitForVisibility('#passwordForm');

        // Fill password
        $crawler->filter('#passwordForm')->form([
            'password1' => '123changePassword',
        ]);

        $crawler = $client->waitForEnabled('#password2');
        $crawler->filter('#passwordForm')->form([
            'password1' => '123changePassword',
            'password2' => '123changePassword',
        ]);

        // Submit the form
        $crawler = $client->waitForEnabled('#savePassword');
        $crawler->filter('#savePassword')->click();
        $client->waitFor('.alert-success');

        // Log Out
        $this->logout($client);
        $this->assertSelectorTextContains('.h3', 'Popular Listing');
    }

    public function testChangePasswordBack(): void
    {
        $client = $this->authAsAdmin($this);
        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy([
                'username' => 'user',
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/user/'.$user.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'user[password]' => 'user',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects();
    }
}
