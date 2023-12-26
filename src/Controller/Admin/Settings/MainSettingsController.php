<?php

declare(strict_types=1);

namespace App\Controller\Admin\Settings;

use App\Form\Type\MainSettingsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainSettingsController extends AbstractSettingsController
{
    #[Route(path: '/admin/settings', name: 'admin_settings')]
    public function settings(Request $request): Response
    {
        $form = $this->createForm(MainSettingsType::class, $this->settings);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->updateSettings($form->getNormData());

            return $this->redirectToRoute('admin_settings');
        }

        return $this->render('admin/settings/main_settings.html.twig', [
            'site' => $this->settings,
            'form' => $form,
        ]);
    }
}
