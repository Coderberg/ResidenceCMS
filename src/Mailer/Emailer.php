<?php

declare(strict_types=1);

namespace App\Mailer;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class Emailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(\Swift_Mailer $mailer, SessionInterface $session, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->session = $session;
        $this->translator = $translator;
    }

    public function create(array $formData, $to): \Swift_Message
    {
        return (new \Swift_Message($this->translator->trans('email.new_message')))
            ->setFrom([$formData['email'] => $formData['name']])
            ->setReplyTo($formData['email'])
            ->setTo($to)
            ->setBody($formData['message'], 'text/plain');
    }

    public function sendEmail(FormInterface $form, $to)
    {
        $this->mailer->send($this->create($form->getData(), $to));
        $this->session->getFlashBag()->add('success', 'message.was_sent');
    }
}
