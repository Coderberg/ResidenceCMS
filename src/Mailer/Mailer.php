<?php

declare(strict_types=1);

namespace App\Mailer;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(Email $email): void
    {
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }
    }
}
