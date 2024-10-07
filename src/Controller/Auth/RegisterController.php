<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Entity\Profile;
use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use App\Message\SendEmailConfirmationLink;
use App\Repository\SettingsRepository;
use App\Security\RegistrationFormAuthenticator;
use App\Service\Admin\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

final class RegisterController extends BaseController implements AuthController
{
    private readonly array $settings;

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly RegistrationFormAuthenticator $authenticator,
        private readonly Security $security,
        private readonly UserAuthenticatorInterface $userAuthenticator,
        private readonly UserService $service,
        ManagerRegistry $doctrine,
        RequestStack $requestStack,
        SettingsRepository $settingsRepository,
    ) {
        parent::__construct($settingsRepository, $doctrine);
        $this->settings = $this->site($requestStack->getCurrentRequest());
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request): ?Response
    {
        if ($this->security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('user_property');
        } elseif ('1' !== $this->settings['anyone_can_register']) {
            $this->addFlash('danger', 'message.registration_suspended');

            return $this->redirectToRoute('property');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setProfile(new Profile());
            $this->service->create($user);
            $this->messageBus->dispatch(new SendEmailConfirmationLink($user));

            return $this->authenticate($user, $request);
        }

        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form,
            'site' => $this->settings,
        ]);
    }

    private function authenticate(User $user, Request $request): ?Response
    {
        return $this->userAuthenticator->authenticateUser(
            $user,
            $this->authenticator,
            $request
        );
    }
}
