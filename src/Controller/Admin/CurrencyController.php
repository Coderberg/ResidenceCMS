<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Currency;
use App\Form\Type\CurrencyType;
use App\Repository\CurrencyRepository;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CurrencyController extends BaseController
{
    #[Route(path: '/admin/currency', name: 'admin_currency')]
    public function index(Request $request, CurrencyRepository $repository): Response
    {
        return $this->render('admin/currency/index.html.twig', [
            'site' => $this->site($request),
            'currencies' => $repository->findAll(),
        ]);
    }

    #[Route(path: '/admin/currency/new', name: 'admin_currency_new')]
    public function new(Request $request): Response
    {
        $currency = new Currency();

        $form = $this->createForm(CurrencyType::class, $currency)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($currency);
            $em->flush();

            $this->addFlash('success', 'message.created');

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_currency_new');
            }

            return $this->redirectToRoute('admin_currency');
        }

        return $this->render('admin/currency/new.html.twig', [
            'site' => $this->site($request),
            'currency' => $currency,
            'form' => $form,
        ]);
    }

    /**
     * Displays a form to edit an existing Currency entity.
     */
    #[Route(
        path: '/admin/currency/{id}/edit',
        name: 'admin_currency_edit',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Currency $currency): Response
    {
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_currency');
        }

        return $this->render('admin/currency/edit.html.twig', [
            'site' => $this->site($request),
            'form' => $form,
        ]);
    }

    /**
     * Deletes a Currency entity.
     */
    #[Route(
        path: '/currency/{id}/delete',
        name: 'admin_currency_delete',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Currency $currency): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->getPayload()->get('token'))) {
            return $this->redirectToRoute('admin_currency');
        }

        $em = $this->doctrine->getManager();
        $em->remove($currency);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_currency');
    }
}
