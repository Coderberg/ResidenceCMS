<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Form\Type\AreaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AreaController extends AbstractController
{
    /**
     * @Route("/admin/area/new", name="admin_area_new")
     */
    public function new(Request $request): Response
    {
        $area = new Area();

        $form = $this->createForm(AreaType::class, $area)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($area);
            $em->flush();

            $this->addFlash('success', 'message.created');

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_area_new');
            }

            return $this->redirectToRoute('admin_locality');
        }

        return $this->render('admin/area/new.html.twig', [
            'area' => $area,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Area entity.
     *
     * @Route("/admin/area/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_area_edit")
     */
    public function edit(Request $request, Area $area): Response
    {
        $form = $this->createForm(AreaType::class, $area);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_locality');
        }

        return $this->render('admin/area/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes an Area entity.
     *
     * @Route("/area/{id<\d+>}/delete", methods={"POST"}, name="admin_area_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Area $area): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_locality');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($area);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_locality');
    }
}
