<?php

declare(strict_types=1);

namespace App\Tests\E2E\User;

use App\Tests\Helper\PantherTestHelper;
use App\Tests\Helper\WebTestHelper;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Symfony\Component\Panther\PantherTestCase;

final class GoogleAuthenticatorTest extends PantherTestCase
{
    use PantherTestHelper;
    use WebTestHelper;

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function testSetUpAuthenticator(): void
    {
        // Log In as a User
        $client = self::createPantherClient();
        $this->login($client, 'user', 'user');
        $client->clickLink('Security');
        $client->waitFor('[data-target="#setUpAuthenticator"]');

        // Open the modal window
        $client->clickLink('Set up Google Authenticator');
        $crawler = $client->waitForVisibility('#generatedSecret');
        $secret = $crawler->filter('#generatedSecret')->text();
        $this->assertSame(52, \strlen($secret));

        // Enter wrong one time password
        $crawler->filter('#generate_google_auth_secret')->form([
            'authentication_code' => '123456',
        ]);
        $crawler->filter('#enable2fa')->click();
        $client->waitForVisibility('#twoFactorAuthErrorMessage');

        $this->assertSame(
            'The Google Authenticator code is incorrect or has expired',
            $crawler->filter('#twoFactorAuthErrorMessage')->text()
        );

        // Generate correct one time password
        $ga = new \PHPGangsta_GoogleAuthenticator();
        $oneTimePassword = $ga->getCode($secret);

        // Enter correct one time password
        $crawler->filter('#generate_google_auth_secret')->form([
            'authentication_code' => $oneTimePassword,
        ]);
        $crawler->filter('#enable2fa')->click();
        $client->waitForVisibility('.alert-success');
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function testDisableAuthenticator(): void
    {
        $client = self::createPantherClient();
        $client->clickLink('Security');

        // Open the modal window
        $client->clickLink('Set up Google Authenticator');
        $crawler = $client->waitForVisibility('#disable2fa');

        $crawler->filter('#disable2fa')->click();
        $client->waitForVisibility('.alert-success');

        // Log Out
        $this->logout($client);
        $this->assertSelectorTextContains('.h3', 'Popular Listing');
    }
}
