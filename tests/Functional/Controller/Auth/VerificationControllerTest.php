<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Auth;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class VerificationControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->client = $client = self::createClient([], [
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'user',
        ]);
        $this->entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->helper = $client->getContainer()->get(VerifyEmailHelperInterface::class);
    }

    public function testVerifyUserEmail(): void
    {
        $user = $this->getUser();

        // Reset verification
        $user->setEmailVerifiedAt(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Use incorrect link
        $this->client->request('GET', '/en/email/verify');
        $this->assertResponseRedirects('/en/user/property');
        $this->client->followRedirect();

        // Make sure the user is still not verified
        $this->assertSelectorTextContains('.alert-danger', 'The link to verify your email is invalid');
        $this->assertSelectorTextContains('.alert-warning', 'you need to confirm your email address');
        $this->assertFalse($user->isVerified());

        // Generate link
        $url = $this->generateEmailConfirmationLink($user);
        $this->client->request('GET', $url);
        $this->client->followRedirect();

        // Make sure the user is verified
        $this->assertSelectorTextContains('.alert-success', 'Your email address has been verified');
        $user = $this->getUser();
        $this->assertTrue($user->isVerified());
    }

    private function getUser(): User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user']);
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
