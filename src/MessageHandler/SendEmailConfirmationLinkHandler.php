<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\User;
use App\Mailer\Mailer;
use App\Message\SendEmailConfirmationLink;
use App\Service\Cache\UserDataCache;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

#[AsMessageHandler]
final readonly class SendEmailConfirmationLinkHandler
{
    use UserDataCache;

    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private Mailer $mailer,
        private UrlGeneratorInterface $router,
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendEmailConfirmationLink $sendEmailConfirmationLink): void
    {
        $user = $sendEmailConfirmationLink->getUser();
        $email = $this->buildEmail($user);
        $this->mailer->send($email);
        $this->setConfirmationSentAt($user);
    }

    private function getSender(): Address
    {
        $host = $this->router->getContext()->getHost();

        return new Address('no-reply@'.$host, $host);
    }

    private function getSubject(): string
    {
        return $this->translator->trans('confirmation.email.subject');
    }

    private function getSignatureComponents(User $user): VerifyEmailSignatureComponents
    {
        return $this->verifyEmailHelper->generateSignature(
            'verify_email',
            (string) $user->getId(),
            $user->getEmail()
        );
    }

    private function createContext(VerifyEmailSignatureComponents $signatureComponents): array
    {
        return [
            'signedUrl' => $signatureComponents->getSignedUrl(),
            'expiresAtMessageKey' => $signatureComponents->getExpirationMessageKey(),
            'expiresAtMessageData' => $signatureComponents->getExpirationMessageData(),
        ];
    }

    private function buildEmail(User $user): TemplatedEmail
    {
        $signatureComponents = $this->getSignatureComponents($user);

        return (new TemplatedEmail())
            ->from($this->getSender())
            ->to($user->getEmail())
            ->subject($this->getSubject())
            ->textTemplate('emails/confirmation_email.html.twig')
            ->context($this->createContext($signatureComponents));
    }
}
