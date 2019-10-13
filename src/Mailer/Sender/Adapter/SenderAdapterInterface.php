<?php

declare(strict_types=1);

namespace App\Mailer\Sender\Adapter;

interface SenderAdapterInterface
{
    public function send(string $fromName, string $fromEmail, string $toEmail, string $subject, string $body): void;
}
