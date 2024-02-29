<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\User;
use App\Form\Type\UserType;
use App\Repository\UserRepository;
use App\Service\Admin\UserService;
use App\Utils\UserFormDataSelector;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends BaseController
{
    #[Route(path: '/admin/user', name: 'admin_user')]
    public function index(Request $request, UserRepository $repository): Response
    {
        $users = $repository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'site' => $this->site($request),
            'users' => $users,
        ]);
    }

    #[Route(path: '/admin/user/new', name: 'admin_user_new')]
    public function new(Request $request, UserService $service, UserFormDataSelector $selector): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailVerifiedAt = $selector->getEmailVerifiedAt($form);
            $user->setEmailVerifiedAt($emailVerifiedAt);
            $service->create($user);

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_user_new');
            }

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/new.html.twig', [
            'site' => $this->site($request),
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Displays a form to edit an existing User entity.
     */
    #[Route(
        path: '/admin/user/{id}/edit',
        name: 'admin_user_edit',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, User $user, UserService $service, UserFormDataSelector $selector): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->isVerified() !== $selector->getEmailVerified($form)) {
                $emailVerifiedAt = $selector->getEmailVerifiedAt($form);
                $user->setEmailVerifiedAt($emailVerifiedAt);
            }

            $service->update($user);

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/edit.html.twig', [
            'site' => $this->site($request),
            'form' => $form,
        ]);
    }

    /**
     * Deletes an User entity.
     */
    #[Route(
        path: '/user/{id}/delete',
        name: 'admin_user_delete',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, User $user, UserService $service): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->getPayload()->get('token'))) {
            return $this->redirectToRoute('admin_user');
        }

        $service->remove($user);

        return $this->redirectToRoute('admin_user');
    }
}
