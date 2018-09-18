<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Operation;
use App\Form\OperationType;

final class OperationController extends AbstractController
{
    /**
     * @Route("/admin/operation", name="admin_operation")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Operation::class);

        $operations = $repository->findAll();

        return $this->render('admin/operation/index.html.twig', [
            'operations' => $operations
        ]);
    }

    /**
     * @Route("/admin/operation/new", name="admin_operation_new")
     */
    public function new(Request $request)
    {
        $operation = new Operation();

        $form = $this->createForm(OperationType::class, $operation)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operation->getName();
            $em = $this->getDoctrine()->getManager();
            $em->persist($operation);
            $em->flush();

            $this->addFlash('success', 'message.created');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_operation_new');
            }
            return $this->redirectToRoute('admin_operation');
        }
        return $this->render('admin/operation/new.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Operation entity.
     *
     * @Route("/admin/operation/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_operation_edit")
     */
    public function edit(Request $request, Operation $operation): Response
    {
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');
            return $this->redirectToRoute('admin_operation');
        }
        return $this->render('admin/operation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes an Operation entity.
     *
     * @Route("/operation/{id<\d+>}/delete", methods={"POST"}, name="admin_operation_delete")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function delete(Request $request, Operation $operation): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_operation');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($operation);
        $em->flush();
        $this->addFlash('success', 'message.deleted');
        return $this->redirectToRoute('admin_operation');
    }
}
