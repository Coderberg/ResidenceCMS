<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends BaseController
{
    /**
     * @Route("/register", name="registration")
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // Set their role
            $user->setRoles(['ROLE_USER']);

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $user->setUsername($form->get('username')->getData());
            $user->setPassword($form->get('password')->getData());

            return $this->redirectToRoute('user_property');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'site'             => $this->site(),
        ]);
    }
}
