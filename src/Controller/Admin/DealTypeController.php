<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\DealType;
use App\Form\Type\DealTypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DealTypeController extends AbstractController
{
    /**
     * @Route("/admin/deal_type", name="admin_deal_type")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(DealType::class);

        $dealTypes = $repository->findAll();

        return $this->render('admin/deal_type/index.html.twig', [
            'dealTypes' => $dealTypes,
        ]);
    }

    /**
     * @Route("/admin/deal_type/new", name="admin_deal_type_new")
     */
    public function new(Request $request): Response
    {
        $dealType = new DealType();

        $form = $this->createForm(DealTypeType::class, $dealType)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dealType);
            $em->flush();

            $this->addFlash('success', 'message.created');

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_deal_type_new');
            }

            return $this->redirectToRoute('admin_deal_type');
        }

        return $this->render('admin/deal_type/new.html.twig', [
            'deal_type' => $dealType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing DealType entity.
     *
     * @Route("/admin/deal_type/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_deal_type_edit")
     */
    public function edit(Request $request, DealType $dealType): Response
    {
        $form = $this->createForm(DealTypeType::class, $dealType);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_deal_type');
        }

        return $this->render('admin/deal_type/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a DealType entity.
     *
     * @Route("/deal_type/{id<\d+>}/delete", methods={"POST"}, name="admin_deal_type_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, DealType $dealType): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_deal_type');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($dealType);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_deal_type');
    }
}
