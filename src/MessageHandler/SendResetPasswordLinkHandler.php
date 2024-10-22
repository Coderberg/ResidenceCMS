<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\User;
use App\Mailer\Mailer;
use App\Message\SendResetPasswordLink;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsMessageHandler]
final readonly class SendResetPasswordLinkHandler
{
    public function __construct(
        private Mailer $mailer,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $router,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendResetPasswordLink $sendResetPasswordLink): void
    {
        $user = $sendResetPasswordLink->getUser();

        $email = $this->buildEmail($user);

        $this->mailer->send($email);
    }

    private function getSender(): Address
    {
        $host = $this->router->getContext()->getHost();

        return new Address('no-reply@'.$host, $host);
    }

    private function getSubject(): string
    {
        return $this->translator->trans('resetting.email.subject');
    }

    private function getConfirmationUrl(User $user): string
    {
        return $this->router->generate(
            'password_reset_confirm', ['token' => $user->getConfirmationToken()], 0
        );
    }

    private function buildEmail(User $user): TemplatedEmail
    {
        return (new TemplatedEmail())
              ->from($this->getSender())
              ->to($user->getEmail())
              ->subject($this->getSubject())
              ->textTemplate('emails/reset.txt.twig')
              ->context([
                  'confirmationUrl' => $this->getConfirmationUrl($user),
                  'username' => $user->getUsername(),
              ]);
    }
}
