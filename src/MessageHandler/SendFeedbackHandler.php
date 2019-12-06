<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Contact;
use App\Mailer\Sender\Adapter\SwiftMailerAdapter;
use App\Message\SendFeedback;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendFeedbackHandler implements MessageHandlerInterface
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

    public function __invoke(SendFeedback $sendFeedback)
    {
        /** @var Contact $contact */
        $contact = $sendFeedback->getEmail();

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
