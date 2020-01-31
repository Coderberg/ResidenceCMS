<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\User;
use App\Mailer\Mailer;
use App\Message\SendResetPasswordLink;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SendResetPasswordLinkHandler implements MessageHandlerInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    public function __construct(Mailer $mailer, TranslatorInterface $translator, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->router = $router;
    }

    public function __invoke(SendResetPasswordLink $sendResetPasswordLink)
    {
        /** @var User $user */
        $user = $sendResetPasswordLink->getUser();

        /** @var TemplatedEmail $email */
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
