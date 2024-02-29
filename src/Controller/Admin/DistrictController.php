<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\District;
use App\Form\Type\DistrictType;
use App\Repository\CityRepository;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DistrictController extends BaseController
{
    #[Route(path: '/admin/locations/district', name: 'admin_district')]
    public function index(Request $request, CityRepository $repository): Response
    {
        $cities = $repository->findAll();

        return $this->render('admin/district/index.html.twig', [
            'site' => $this->site($request),
            'cities' => $cities,
        ]);
    }

    #[Route(path: '/admin/locations/district/new', name: 'admin_district_new')]
    public function new(Request $request): Response
    {
        $district = new District();

        $form = $this->createForm(DistrictType::class, $district)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($district);
            $em->flush();

            $this->addFlash('success', 'message.created');

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_district_new');
            }

            return $this->redirectToRoute('admin_district');
        }

        return $this->render('admin/district/new.html.twig', [
            'site' => $this->site($request),
            'district' => $district,
            'form' => $form,
        ]);
    }

    /**
     * Displays a form to edit an existing District entity.
     */
    #[Route(
        path: '/admin/locations/district/{id}/edit',
        name: 'admin_district_edit',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, District $district): Response
    {
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_district');
        }

        return $this->render('admin/district/edit.html.twig', [
            'site' => $this->site($request),
            'form' => $form,
        ]);
    }

    /**
     * Deletes a District entity.
     */
    #[Route(
        path: '/district/{id}/delete',
        name: 'admin_district_delete',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, District $district): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->getPayload()->get('token'))) {
            return $this->redirectToRoute('admin_district');
        }

        $em = $this->doctrine->getManager();
        $em->remove($district);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_district');
    }
}
