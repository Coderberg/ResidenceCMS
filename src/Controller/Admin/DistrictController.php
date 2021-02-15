<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\District;
use App\Form\Type\DistrictType;
use App\Repository\CityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DistrictController extends BaseController
{
    /**
     * @Route("/admin/locations/district", name="admin_district")
     */
    public function index(CityRepository $repository): Response
    {
        $cities = $repository->findAll();

        return $this->render('admin/district/index.html.twig', [
            'site' => $this->site(),
            'cities' => $cities,
        ]);
    }

    /**
     * @Route("/admin/locations/district/new", name="admin_district_new")
     */
    public function new(Request $request): Response
    {
        $district = new District();

        $form = $this->createForm(DistrictType::class, $district)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
            'site' => $this->site(),
            'district' => $district,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing District entity.
     *
     * @Route("/admin/locations/district/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_district_edit")
     */
    public function edit(Request $request, District $district): Response
    {
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_district');
        }

        return $this->render('admin/district/edit.html.twig', [
            'site' => $this->site(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a District entity.
     *
     * @Route("/district/{id<\d+>}/delete", methods={"POST"}, name="admin_district_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, District $district): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_district');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($district);
        $em->flush();
        $this->addFlash('success', 'message.deleted');

        return $this->redirectToRoute('admin_district');
    }
}
