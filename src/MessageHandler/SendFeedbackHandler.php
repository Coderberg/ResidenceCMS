<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Dto\FeedbackDto;
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

    public function __invoke(SendFeedback $sendFeedback): void
    {
        /** @var FeedbackDto $feedback */
        $feedback = $sendFeedback->getFeedback();

        $subject = $this->translator->trans('email.new_message');

        $email = (new Email())
            ->from(new Address($feedback->getFromEmail(), $feedback->getFromName()))
            ->to($feedback->getToEmail())
            ->replyTo($feedback->getFromEmail())
            ->subject($subject)
            ->text($feedback->getMessage())
            ->html($feedback->getMessage());

        $this->mailer->send($email);
    }
}
