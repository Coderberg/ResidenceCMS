<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Entity\Profile;
use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use App\Message\SendEmailConfirmationLink;
use App\Service\Admin\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

final class RegisterController extends BaseController
{
    #[Route('/register', name: 'register')]
    public function register(
        Request $request,
        Security $security,
        MessageBusInterface $messageBus,
        UserService $service): Response
    {
        // if user is already logged in, don't display the registration form again
        if ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('user_property');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setProfile(new Profile());
            $service->create($user);
            $messageBus->dispatch(new SendEmailConfirmationLink($user));

            return $this->redirectToRoute('security_login');
        }

        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form->createView(),
            'site' => $this->site($request),
        ]);
    }
}
