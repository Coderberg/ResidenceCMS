<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\Admin\CategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CategoryController extends BaseController
{
    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function index(CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'site' => $this->site(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/category/new", name="admin_category_new")
     */
    public function new(Request $request, CategoryService $service): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->create($category);

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_category_new');
            }

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category/new.html.twig', [
            'site' => $this->site(),
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/admin/category/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_category_edit")
     */
    public function edit(Request $request, Category $category, CategoryService $service): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service->update($category);

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category/edit.html.twig', [
            'site' => $this->site(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/category/{id<\d+>}/delete", methods={"POST"}, name="admin_category_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Category $category, CategoryService $service): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_category');
        }

        $service->remove($category);

        return $this->redirectToRoute('admin_category');
    }
}
