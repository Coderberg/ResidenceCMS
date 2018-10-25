<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use App\Form\SettingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SettingController extends AbstractController
{
    /**
     * @Route("/admin/setting", name="admin_setting")
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

            return $this->redirectToRoute('admin_setting');
        }

        return $this->render('admin/setting/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
