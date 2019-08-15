<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Locality;
use App\Form\LocalityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LocalityController extends AbstractController
{
    /**
     * @Route("/admin/locality", name="admin_locality")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Locality::class);

        $localities = $repository->findAll();

        return $this->render('admin/locality/index.html.twig', [
            'localities' => $localities,
        ]);
    }

    /**
     * @Route("/admin/locality/new", name="admin_locality_new")
     */
    public function new(Request $request): Response
    {
        $locality = new Locality();

        $form = $this->createForm(LocalityType::class, $locality)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $locality->getName();
            $em = $this->getDoctrine()->getManager();
            $em->persist($locality);
            $em->flush();

            $this->addFlash('success', 'message.created');

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_locality_new');
            }

            return $this->redirectToRoute('admin_locality');
        }

        return $this->render('admin/locality/new.html.twig', [
            'locality' => $locality,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Locality entity.
     *
     * @Route("/admin/locality/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_locality_edit")
     */
    public function edit(Request $request, Locality $locality): Response
    {
        $form = $this->createForm(LocalityType::class, $locality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_locality');
        }

        return $this->render('admin/locality/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Locality entity.
     *
     * @Route("/locality/{id<\d+>}/delete", methods={"POST"}, name="admin_locality_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Locality $locality): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_locality');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($locality);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_locality');
    }
}
