<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Auth;

use App\Entity\User;
use App\Tests\Helper\WebTestHelper;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelper;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class VerificationControllerTest extends WebTestCase
{
    use WebTestHelper;

    private KernelBrowser $client;
    private ?ObjectManager $entityManager;
    private VerifyEmailHelper $helper;

    protected function setUp(): void
    {
        $this->client = $client = $this->authAsUser($this);
        $this->entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->helper = $client->getContainer()->get(VerifyEmailHelperInterface::class);
    }

    public function testVerifyUserEmail(): void
    {
        $user = $this->getUser($this->client, 'user');

        // Reset verification
        $user->setEmailVerifiedAt(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Use incorrect link
        $this->client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/en/email/verify');
        $this->assertResponseRedirects('/en/user/property');
        $this->client->followRedirect();

        // Make sure the user is still not verified
        $this->assertSelectorTextContains('.alert-danger', 'The link to verify your email is invalid');
        $this->assertSelectorTextContains('.alert-warning', 'you need to confirm your email address');
        $this->assertFalse($user->isVerified());

        // Make sure an unverified user cannot add new properties
        $this->client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/en/user/property/new');
        $this->assertResponseRedirects('/en/user/property');

        // Generate link
        $url = $this->generateEmailConfirmationLink($user);
        $this->client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, $url);
        $this->client->followRedirect();

        // Make sure the user is verified
        $this->assertSelectorTextContains('.alert-success', 'Your email address has been verified');
        $user = $this->getUser($this->client, 'user');
        $this->assertTrue($user->isVerified());
    }

    private function generateEmailConfirmationLink(User $user): string
    {
        return $this->helper->generateSignature(
            'verify_email',
            (string) $user->getId(),
            $user->getEmail()
        )->getSignedUrl();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
