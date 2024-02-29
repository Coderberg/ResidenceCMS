<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Menu;
use App\Form\Type\MenuType;
use App\Repository\MenuRepository;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class MenuController extends BaseController
{
    #[Route(path: '/admin/menu', name: 'admin_menu')]
    public function index(Request $request, MenuRepository $repository): Response
    {
        return $this->render('admin/menu/index.html.twig', [
            'site' => $this->site($request),
            'menu' => $repository->findItems(),
        ]);
    }

    #[Route(path: '/admin/menu/new', name: 'admin_menu_new')]
    public function new(Request $request): Response
    {
        $menu = new Menu();

        $form = $this->createForm(MenuType::class, $menu)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($menu);
            $em->flush();

            $this->addFlash('success', 'message.created');

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_menu_new');
            }

            return $this->redirectToRoute('admin_menu');
        }

        return $this->render('admin/menu/new.html.twig', [
            'site' => $this->site($request),
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    /**
     * Displays a form to edit an existing menu item.
     */
    #[Route(
        path: '/admin/menu/{id}/edit',
        name: 'admin_menu_edit',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Menu $menu): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_menu');
        }

        return $this->render('admin/menu/edit.html.twig', [
            'site' => $this->site($request),
            'form' => $form,
        ]);
    }

    /**
     * Deletes a menu item.
     */
    #[Route(
        path: '/admin/menu/{id}/delete',
        name: 'admin_menu_delete',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST']
    )]
    public function delete(Request $request, Menu $menu): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->getPayload()->get('token'))) {
            return $this->redirectToRoute('admin_menu');
        }

        $em = $this->doctrine->getManager();
        $em->remove($menu);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_menu');
    }
}
