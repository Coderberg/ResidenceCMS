<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Neighborhood;
use App\Form\Type\NeighborhoodType;
use App\Repository\CityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class NeighborhoodController extends BaseController
{
    /**
     * @Route("/admin/locations/neighborhood", name="admin_neighborhood")
     */
    public function index(CityRepository $repository): Response
    {
        $cities = $repository->findAll();

        return $this->render('admin/neighborhood/index.html.twig', [
            'site' => $this->site(),
            'cities' => $cities,
        ]);
    }

    /**
     * @Route("/admin/locations/neighborhood/new", name="admin_neighborhood_new")
     */
    public function new(Request $request): Response
    {
        $neighborhood = new Neighborhood();

        $form = $this->createForm(NeighborhoodType::class, $neighborhood)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($neighborhood);
            $em->flush();

            $this->addFlash('success', 'message.created');

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_neighborhood_new');
            }

            return $this->redirectToRoute('admin_neighborhood');
        }

        return $this->render('admin/neighborhood/new.html.twig', [
            'site' => $this->site(),
            'neighborhood' => $neighborhood,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Neighborhood entity.
     *
     * @Route("/admin/locations/neighborhood/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_neighborhood_edit")
     */
    public function edit(Request $request, Neighborhood $neighborhood): Response
    {
        $form = $this->createForm(NeighborhoodType::class, $neighborhood);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_neighborhood');
        }

        return $this->render('admin/neighborhood/edit.html.twig', [
            'site' => $this->site(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Neighborhood entity.
     *
     * @Route("/neighborhood/{id<\d+>}/delete", methods={"POST"}, name="admin_neighborhood_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Neighborhood $neighborhood): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_neighborhood');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($neighborhood);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_neighborhood');
    }
}
