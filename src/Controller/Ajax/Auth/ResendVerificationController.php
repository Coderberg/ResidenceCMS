<?php

declare(strict_types=1);

namespace App\Controller\Ajax\Auth;

use App\Controller\Ajax\AjaxController;
use App\Entity\User;
use App\Message\SendEmailConfirmationLink;
use App\Service\Cache\UserDataCache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ResendVerificationController extends AbstractController implements AjaxController
{
    use UserDataCache;

    #[Route('/auth/should_link_be_visible', name: 'check_confirmation', methods: ['GET'])]
    public function shouldLinkBeVisible(): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return new JsonResponse(['display' => $this->isSendingAllowed($user)]);
    }

    #[Route('/auth/resend', name: 'resend_confirmation', methods: ['POST'])]
    public function resendEmail(MessageBusInterface $messageBus, TranslatorInterface $translator): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($this->isSendingAllowed($user)) {
            $messageBus->dispatch(new SendEmailConfirmationLink($user));
        } else {
            return new JsonResponse(
                ['message' => 'There is no need to resend this email'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $translated = $translator->trans('confirmation.email.success');

        return new JsonResponse(['message' => $translated.' '.$user->getEmail()]);
    }

    private function isSendingAllowed(User $user): bool
    {
        if ($user->getEmailVerifiedAt() instanceof \DateTime) {
            return false;
        }

        $sentAt = $this->getConfirmationSentAt($user);

        if ($sentAt instanceof \DateTimeInterface) {
            return ((int) date_diff(new \DateTime('now'), $sentAt)
                ->format('%i')) > 60;
        }

        return true;
    }
}
