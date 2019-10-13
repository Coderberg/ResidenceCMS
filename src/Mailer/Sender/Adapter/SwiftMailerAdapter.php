<?php

declare(strict_types=1);

namespace App\Mailer\Sender\Adapter;

use Swift_Message;

final class SwiftMailerAdapter implements SenderAdapterInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $fromName, string $fromEmail, string $toEmail, string $subject, string $body): void
    {
        // Create a message
        $message = (new Swift_Message($subject))
            ->setFrom([$fromEmail => $fromName])
            ->setTo([$toEmail])
            ->setReplyTo([$fromEmail])
            ->setBody($body);

        // Send the message
        $this->mailer->send($message);
    }
}
