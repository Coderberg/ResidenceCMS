<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Currency;
use App\Form\Type\CurrencyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CurrencyController extends AbstractController
{
    /**
     * @Route("/admin/currency", name="admin_currency")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Currency::class);

        $currencies = $repository->findAll();

        return $this->render('admin/currency/index.html.twig', [
            'currencies' => $currencies,
        ]);
    }

    /**
     * @Route("/admin/currency/new", name="admin_currency_new")
     */
    public function new(Request $request): Response
    {
        $currency = new Currency();

        $form = $this->createForm(CurrencyType::class, $currency)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
            'currency' => $currency,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Currency entity.
     *
     * @Route("/admin/currency/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_currency_edit")
     */
    public function edit(Request $request, Currency $currency): Response
    {
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_currency');
        }

        return $this->render('admin/currency/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Currency entity.
     *
     * @Route("/currency/{id<\d+>}/delete", methods={"POST"}, name="admin_currency_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Currency $currency): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_currency');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($currency);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_currency');
    }
}
