<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Repository\UserRepository;
use App\Service\Admin\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index(UserRepository $repository): Response
    {
        $users = $repository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/new", name="admin_user_new")
     */
    public function new(Request $request, UserService $service): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->create($user);

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_user_new');
            }

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/admin/user/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_user_edit")
     */
    public function edit(Request $request, User $user, UserService $service): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service->update($user);

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes an User entity.
     *
     * @Route("/user/{id<\d+>}/delete", methods={"POST"}, name="admin_user_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user, UserService $service): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_user');
        }

        $service->remove($user);

        return $this->redirectToRoute('admin_user');
    }
}
