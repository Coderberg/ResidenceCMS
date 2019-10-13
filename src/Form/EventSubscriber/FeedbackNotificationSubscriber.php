<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber;

use App\Entity\Contact;
use App\Event\ContactFormSubmittedEvent;
use App\Mailer\Sender\Adapter\SwiftMailerAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FeedbackNotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var SwiftMailerAdapter
     */
    private $mailer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(SwiftMailerAdapter $mailer, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactFormSubmittedEvent::class => 'onContactFormSubmitted',
        ];
    }

    public function onContactFormSubmitted(ContactFormSubmittedEvent $event): void
    {
        /** @var Contact $contact */
        $contact = $event->getContact();

        $subject = $this->translator->trans('email.new_message');

        $this->mailer->send(
            $contact->getFromName(),
            $contact->getFromEmail(),
            $contact->getToEmail(),
            $subject,
            $contact->getMessage()
        );
    }
}
