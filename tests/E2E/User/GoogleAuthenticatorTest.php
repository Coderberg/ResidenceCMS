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

    private const PRIMARY_BUTTON = '.btn-primary';

    private static string $secret = 'initial';

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
        $this->assertSame(52, mb_strlen($secret));

        self::$secret = $secret;

        // Enter wrong one time password
        $crawler->filter('#generate_google_auth_secret')->form([
            'authentication_code' => random_int(100000, 999999),
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

        // Log Out
        $this->logout($client);
        $this->assertSelectorTextContains('.h3', 'Popular Listing');
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function testDisableAuthenticator(): void
    {
        $client = self::createPantherClient();

        // Try to log in
        $client->clickLink('Log in');
        $crawler = $client->waitForVisibility('[name="login_form"]');

        // Enter credentials
        $crawler->filter('[name="login_form"]')->form([
            'login_form[username]' => 'user',
            'login_form[password]' => 'user',
        ]);
        $crawler->filter(self::PRIMARY_BUTTON)->click();

        // Try wrong one time password
        $crawler = $client->waitFor('#_auth_code');
        $crawler->filter('#otp')->form([
            '_auth_code' => random_int(100000, 999999),
        ]);
        $crawler->filter(self::PRIMARY_BUTTON)->click();
        $this->assertSelectorTextContains('.card-header', 'Google Authenticator code');

        // Generate correct one time password
        $ga = new \PHPGangsta_GoogleAuthenticator();
        $oneTimePassword = $ga->getCode(self::$secret);

        $crawler = $client->waitForVisibility('#otp');

        // Enter valid one time password
        $crawler->filter('#otp')->form([
            '_auth_code' => $oneTimePassword,
        ]);

        $crawler->filter(self::PRIMARY_BUTTON)->click();
        $client->waitFor('h3');
        $this->assertSelectorTextContains('h3', 'My properties');

        // Go to the Security page and disable Google Authenticator
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
