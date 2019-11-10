<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Metro;
use App\Form\Type\MetroType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MetroController extends AbstractController
{
    /**
     * @Route("/admin/metro/new", name="admin_metro_new")
     */
    public function new(Request $request): Response
    {
        $metro = new Metro();

        $form = $this->createForm(MetroType::class, $metro)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($metro);
            $em->flush();

            $this->addFlash('success', 'message.created');

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_metro_new');
            }

            return $this->redirectToRoute('admin_city');
        }

        return $this->render('admin/metro/new.html.twig', [
            'metro' => $metro,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Metro entity.
     *
     * @Route("/admin/metro/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_metro_edit")
     */
    public function edit(Request $request, Metro $metro): Response
    {
        $form = $this->createForm(MetroType::class, $metro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_city');
        }

        return $this->render('admin/metro/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Metro entity.
     *
     * @Route("/metro/{id<\d+>}/delete", methods={"POST"}, name="admin_metro_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Metro $metro): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_city');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($metro);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_city');
    }
}
