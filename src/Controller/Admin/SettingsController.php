<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Form\Type\SettingsType;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class SettingsController extends AbstractController
{
    /**
     * @Route("/admin/setting", name="admin_setting")
     */
    public function index(Request $request, SettingsRepository $repository)
    {
        $settings = $repository->findAllAsArray();

        $form = $this->createForm(SettingsType::class, $settings);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->updateSettings($form->getNormData());
            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_setting');
        }

        return $this->render('admin/setting/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
