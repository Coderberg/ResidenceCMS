<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\DealType;
use App\Form\Type\DealTypeType;
use App\Service\Admin\DealTypeService;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DealTypeController extends BaseController
{
    #[Route(path: '/admin/deal_type', name: 'admin_deal_type')]
    public function index(Request $request): Response
    {
        $repository = $this->doctrine->getRepository(DealType::class);

        $dealTypes = $repository->findAll();

        return $this->render('admin/deal_type/index.html.twig', [
            'site' => $this->site($request),
            'dealTypes' => $dealTypes,
        ]);
    }

    #[Route(path: '/admin/deal_type/new', name: 'admin_deal_type_new')]
    public function new(Request $request, DealTypeService $service): Response
    {
        $dealType = new DealType();

        $form = $this->createForm(DealTypeType::class, $dealType)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->create($dealType);

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_deal_type_new');
            }

            return $this->redirectToRoute('admin_deal_type');
        }

        return $this->render('admin/deal_type/new.html.twig', [
            'site' => $this->site($request),
            'deal_type' => $dealType,
            'form' => $form,
        ]);
    }

    /**
     * Displays a form to edit an existing DealType entity.
     */
    #[Route(
        path: '/admin/deal_type/{id}/edit',
        name: 'admin_deal_type_edit',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, DealType $dealType, DealTypeService $service): Response
    {
        $form = $this->createForm(DealTypeType::class, $dealType);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service->update($dealType);

            return $this->redirectToRoute('admin_deal_type');
        }

        return $this->render('admin/deal_type/edit.html.twig', [
            'site' => $this->site($request),
            'form' => $form,
        ]);
    }

    /**
     * Deletes a DealType entity.
     */
    #[Route(
        path: '/deal_type/{id}/delete',
        name: 'admin_deal_type_delete',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, DealType $dealType, DealTypeService $service): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->getPayload()->get('token'))) {
            return $this->redirectToRoute('admin_deal_type');
        }

        $service->remove($dealType);

        return $this->redirectToRoute('admin_deal_type');
    }
}
