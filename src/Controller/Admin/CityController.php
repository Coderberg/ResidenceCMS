<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\City;
use App\Form\Type\CityType;
use App\Repository\CityRepository;
use App\Service\Admin\CityService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CityController extends BaseController
{
    /**
     * @Route("/admin/locations/city", name="admin_city")
     */
    public function index(Request $request, CityRepository $repository): Response
    {
        $cities = $repository->findAll();

        return $this->render('admin/city/index.html.twig', [
            'site' => $this->site($request),
            'cities' => $cities,
        ]);
    }

    /**
     * @Route("/admin/locations/city/new", name="admin_city_new")
     */
    public function new(Request $request, CityService $service): Response
    {
        $city = new City();

        $form = $this->createForm(CityType::class, $city)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->create($city);

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_city_new');
            }

            return $this->redirectToRoute('admin_city');
        }

        return $this->render('admin/city/new.html.twig', [
            'site' => $this->site($request),
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing City entity.
     *
     * @Route("/admin/locations/city/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_city_edit")
     */
    public function edit(Request $request, City $city, CityService $service): Response
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->update($city);

            return $this->redirectToRoute('admin_city');
        }

        return $this->render('admin/city/edit.html.twig', [
            'site' => $this->site($request),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a City entity.
     *
     * @Route("/city/{id<\d+>}/delete", methods={"POST"}, name="admin_city_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, City $city, CityService $service): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_city');
        }
        $service->remove($city);

        return $this->redirectToRoute('admin_city');
    }
}
