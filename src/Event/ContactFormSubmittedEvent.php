<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Contact;
use Symfony\Contracts\EventDispatcher\Event;

final class ContactFormSubmittedEvent extends Event
{
    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }
}
