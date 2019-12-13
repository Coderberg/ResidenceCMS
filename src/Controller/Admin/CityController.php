<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\City;
use App\Form\Type\CityType;
use App\Repository\CityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CityController extends AbstractController
{
    /**
     * @Route("/admin/locations/city", name="admin_city")
     */
    public function index(CityRepository $repository): Response
    {
        $cities = $repository->findAll();

        return $this->render('admin/city/index.html.twig', [
            'cities' => $cities,
        ]);
    }

    /**
     * @Route("/admin/locations/city/new", name="admin_city_new")
     */
    public function new(Request $request): Response
    {
        $city = new City();

        $form = $this->createForm(CityType::class, $city)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();

            $this->addFlash('success', 'message.created');

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_city_new');
            }

            return $this->redirectToRoute('admin_city');
        }

        return $this->render('admin/city/new.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing City entity.
     *
     * @Route("/admin/locations/city/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_city_edit")
     */
    public function edit(Request $request, City $city): Response
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_city');
        }

        return $this->render('admin/city/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a City entity.
     *
     * @Route("/city/{id<\d+>}/delete", methods={"POST"}, name="admin_city_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, City $city): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_city');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($city);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_city');
    }
}
