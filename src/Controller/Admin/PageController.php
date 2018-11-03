<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Form\PageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PageController extends AbstractController
{
    /**
     * @Route("/admin/page", defaults={"page": "1"}, methods={"GET"}, name="admin_page")
     * @Route("/admin/page/{page<[1-9]\d*>}", methods={"GET"}, name="admin_page_paginated")
     */
    public function index(?int $page)
    {
        // Get pages
        $repository = $this->getDoctrine()->getRepository(Page::class);

        $pages = $repository->findLatest($page);

        return $this->render('admin/page/index.html.twig', [
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/admin/page/new", name="admin_page_new")
     */
    public function new(Request $request)
    {
        $page = new Page();

        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            $this->addFlash('success', 'message.created');

            return $this->redirectToRoute('page', ['slug' => $page->getSlug()]);
        }

        return $this->render('admin/page/new.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/admin/page/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_page_edit")
     */
    public function edit(Request $request, Page $page): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('page', ['slug' => $page->getSlug()]);
        }

        return $this->render('admin/page/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Page entity.
     *
     * @Route("/page/{id<\d+>}/delete", methods={"POST"}, name="admin_page_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Page $page): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_page');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_page');
    }
}
