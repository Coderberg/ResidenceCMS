<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Auth;

use App\Entity\User;
use App\Tests\Helper\WebTestHelper;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DomCrawler\Crawler;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelper;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class ResendVerificationControllerTest extends WebTestCase
{
    use WebTestHelper;

    private KernelBrowser $client;
    private ObjectManager $entityManager;
    private ?User $user;
    private VerifyEmailHelper $helper;

    protected function setUp(): void
    {
        $this->client = $client = $this->authAsUser($this);
        $this->entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->user = $this->getUser($this->client, 'user');
        $this->helper = $client->getContainer()->get(VerifyEmailHelperInterface::class);

        $cache = new FilesystemAdapter();
        $cache->clear();
    }

    public function testResetUserVerification(): void
    {
        // Reset verification
        $this->user->setEmailVerifiedAt(null);
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();

        // Make sure the user is not verified
        $this->client->request('GET', '/en/user/property');
        $this->assertSelectorTextContains('.alert-warning', 'you need to confirm your email address');
        $this->assertFalse($this->user->isVerified());
    }

    public function testCsrfProtection(): void
    {
        $this->client->request('GET', '/en/auth/should_link_be_visible', []);
        $this->assertResponseStatusCodeSame(419);

        $this->client->request('POST', '/en/auth/resend', []);
        $this->assertResponseStatusCodeSame(419);
    }

    public function testShouldLinkBeVisible(): void
    {
        $token = $this->findResendLink()->attr('data-token');
        $this->client->request('POST', '/en/auth/should_link_be_visible', [
            'csrf_token' => $token,
        ]);
        $this->assertResponseStatusCodeSame(405);

        $this->client->request('GET', '/en/auth/should_link_be_visible', [
            'csrf_token' => $token,
        ]);
        $this->assertResponseIsSuccessful();
    }

    public function testResendEmail(): void
    {
        $token = $this->findResendLink()->attr('data-token');
        $url = $this->findResendLink()->attr('data-path');

        $this->client->request('POST', $url, [
            'csrf_token' => $token,
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertEmailCount(1);

        $this->client->request('POST', $url, [
            'csrf_token' => $token,
        ]);
        $this->assertResponseIsUnprocessable();
        $this->assertEmailCount(0);
    }

    public function testVerifyUser(): void
    {
        // Generate link
        $url = $this->generateEmailConfirmationLink($this->user);
        $this->client->request('GET', $url);
        $this->client->followRedirect();

        // Make sure the user is verified
        $this->assertSelectorTextContains('.alert-success', 'Your email address has been verified');
        $this->assertTrue($this->user->isVerified());
    }

    private function findResendLink(): Crawler
    {
        $crawler = $this->client->request('GET', '/en/user/property');

        return $crawler->filter('#resend');
    }

    private function generateEmailConfirmationLink(User $user): string
    {
        return $this->helper->generateSignature(
            'verify_email',
            (string) $user->getId(),
            $user->getEmail()
        )->getSignedUrl();
    }
}
