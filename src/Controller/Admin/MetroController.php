<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Metro;
use App\Form\Type\MetroType;
use App\Repository\CityRepository;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class MetroController extends BaseController
{
    #[Route(path: '/admin/locations/metro', name: 'admin_metro')]
    public function index(Request $request, CityRepository $repository): Response
    {
        $cities = $repository->findAll();

        return $this->render('admin/metro/index.html.twig', [
            'site' => $this->site($request),
            'cities' => $cities,
        ]);
    }

    #[Route(path: '/admin/locations/metro/new', name: 'admin_metro_new')]
    public function new(Request $request): Response
    {
        $metro = new Metro();

        $form = $this->createForm(MetroType::class, $metro)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($metro);
            $em->flush();

            $this->addFlash('success', 'message.created');

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_metro_new');
            }

            return $this->redirectToRoute('admin_metro');
        }

        return $this->render('admin/metro/new.html.twig', [
            'site' => $this->site($request),
            'metro' => $metro,
            'form' => $form,
        ]);
    }

    /**
     * Displays a form to edit an existing Metro entity.
     */
    #[Route(
        path: '/admin/locations/metro/{id}/edit',
        name: 'admin_metro_edit',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Metro $metro): Response
    {
        $form = $this->createForm(MetroType::class, $metro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_metro');
        }

        return $this->render('admin/metro/edit.html.twig', [
            'site' => $this->site($request),
            'form' => $form,
        ]);
    }

    /**
     * Deletes a Metro entity.
     */
    #[Route(
        path: '/metro/{id}/delete',
        name: 'admin_metro_delete',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Metro $metro): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->getPayload()->get('token'))) {
            return $this->redirectToRoute('admin_metro');
        }

        $em = $this->doctrine->getManager();
        $em->remove($metro);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_metro');
    }
}
