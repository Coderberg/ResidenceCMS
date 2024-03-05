<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Neighborhood;
use App\Form\Type\NeighborhoodType;
use App\Repository\CityRepository;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class NeighborhoodController extends BaseController
{
    #[Route(path: '/admin/locations/neighborhood', name: 'admin_neighborhood')]
    public function index(Request $request, CityRepository $repository): Response
    {
        $cities = $repository->findAll();

        return $this->render('admin/neighborhood/index.html.twig', [
            'site' => $this->site($request),
            'cities' => $cities,
        ]);
    }

    #[Route(path: '/admin/locations/neighborhood/new', name: 'admin_neighborhood_new')]
    public function new(Request $request): Response
    {
        $neighborhood = new Neighborhood();

        $form = $this->createForm(NeighborhoodType::class, $neighborhood)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
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
            'site' => $this->site($request),
            'neighborhood' => $neighborhood,
            'form' => $form,
        ]);
    }

    /**
     * Displays a form to edit an existing Neighborhood entity.
     */
    #[Route(
        path: '/admin/locations/neighborhood/{id}/edit',
        name: 'admin_neighborhood_edit',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Neighborhood $neighborhood): Response
    {
        $form = $this->createForm(NeighborhoodType::class, $neighborhood);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_neighborhood');
        }

        return $this->render('admin/neighborhood/edit.html.twig', [
            'site' => $this->site($request),
            'form' => $form,
        ]);
    }

    /**
     * Deletes a Neighborhood entity.
     */
    #[Route(
        path: '/neighborhood/{id}/delete',
        name: 'admin_neighborhood_delete',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Neighborhood $neighborhood): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->getPayload()->get('token'))) {
            return $this->redirectToRoute('admin_neighborhood');
        }

        $em = $this->doctrine->getManager();
        $em->remove($neighborhood);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_neighborhood');
    }
}
