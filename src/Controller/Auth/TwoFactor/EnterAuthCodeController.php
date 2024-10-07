<?php

declare(strict_types=1);

namespace App\Controller\Auth\TwoFactor;

use App\Controller\Traits\MenuTrait;
use App\Repository\SettingsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class EnterAuthCodeController implements TwoFactorFormRendererInterface
{
    use MenuTrait;

    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly SettingsRepository $settingsRepository,
        protected ManagerRegistry $doctrine,
    ) {
    }

    public function site(Request $request): array
    {
        $settings = $this->settingsRepository->findAllAsArray();

        $this->setDoctrine($this->doctrine);

        $menu = $this->menu($request);

        return array_merge($settings, $menu);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function renderForm(Request $request, array $templateVars): Response
    {
        $content = $this->twigEnvironment->render('auth/two_factor/two_factor_form.html.twig', [
            'robots' => 'noindex',
            'site' => $this->site($request),
        ]);

        return (new Response())
            ->setContent($content);
    }
}
