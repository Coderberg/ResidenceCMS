<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Form\Type\PageType;
use App\Repository\PageRepository;
use App\Service\Admin\PageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PageController extends AbstractController
{
    /**
     * @Route("/admin/page", defaults={"page": "1"}, methods={"GET"}, name="admin_page")
     */
    public function index(Request $request, PageRepository $repository): Response
    {
        // Get pages
        $pages = $repository->findLatest($request);

        return $this->render('admin/page/index.html.twig', [
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/admin/page/new", name="admin_page_new")
     */
    public function new(Request $request, PageService $pageService): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pageService->create($page);

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
    public function delete(Request $request, Page $page, PageService $pageService): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_page');
        }

        $pageService->delete($page);

        return $this->redirectToRoute('admin_page');
    }
}
