<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Contact;

final class SendFeedback
{
    private $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function getEmail(): Contact
    {
        return $this->contact;
    }
}
