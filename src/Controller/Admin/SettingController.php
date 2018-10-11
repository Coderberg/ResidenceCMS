<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Setting;
use App\Form\SettingType;

final class SettingController extends AbstractController
{
    /**
     * @Route("/admin/homepage_setting", name="admin_homepage_setting")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Setting::class);

        $settings = $repository->findAll()[0];

        $form = $this->createForm(SettingType::class, $settings);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.updated');
            return $this->redirectToRoute('admin_homepage_setting');
        }
        return $this->render('admin/setting/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
