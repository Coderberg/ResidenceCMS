<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Contact;
use App\Mailer\Mailer;
use App\Message\SendFeedback;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SendFeedbackHandler implements MessageHandlerInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(Mailer $mailer, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public function __invoke(SendFeedback $sendFeedback)
    {
        /** @var Contact $contact */
        $contact = $sendFeedback->getContact();

        $subject = $this->translator->trans('email.new_message');

        $email = (new Email())
            ->from(new Address($contact->getFromEmail(), $contact->getFromName()))
            ->to($contact->getToEmail())
            ->replyTo($contact->getFromEmail())
            ->subject($subject)
            ->text($contact->getMessage())
            ->html($contact->getMessage());

        $this->mailer->send($email);
    }
}
