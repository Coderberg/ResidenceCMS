<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Service\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class GoogleAuthenticatorService extends AbstractService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly GoogleAuthenticatorAdapter $googleAuthenticator,
        private readonly TranslatorInterface $translator,
        CsrfTokenManagerInterface $tokenManager,
        RequestStack $requestStack,
    ) {
        parent::__construct($tokenManager, $requestStack);
    }

    /**
     * @throws \LogicException
     *
     * @return string[]
     */
    public function generateSecret(User $user): array
    {
        if ($user->isGoogleAuthenticatorEnabled()) {
            throw new \LogicException($this->translator->trans('2fa.errors.secret_is_already_set'));
        }

        $secret = $this->googleAuthenticator->generateSecret();
        $user->setGoogleAuthenticatorSecret($secret);
        $qrContent = $this->googleAuthenticator->getQRContent($user);

        parse_str($qrContent, $queryArray);

        return [
            'secret' => $queryArray['secret'],
            'qr_code' => $this->getEncodedQrCode($qrContent),
        ];
    }

    /**
     * @throws \Exception
     */
    public function setSecret(User $user, ?string $secret, ?string $authenticationCode): void
    {
        if (!$authenticationCode || !$secret) {
            throw new \Exception($this->translator->trans('2fa.errors.cannot_enable_ga'));
        }

        $user->setGoogleAuthenticatorSecret($secret);

        if (!$this->googleAuthenticator->checkCode($user, $authenticationCode)) {
            throw new \Exception($this->translator->trans('2fa.errors.incorrect_ga_code'));
        }

        $this->entityManager->flush();
        $this->addFlash('success', '2fa.messages.enabled');
    }

    public function deleteSecret(User $user): void
    {
        $user->setGoogleAuthenticatorSecret(null);
        $this->entityManager->flush();
        $this->addFlash('success', '2fa.messages.disabled');
    }

    private function getEncodedQrCode(string $qrCodeContent): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($qrCodeContent)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(200)
            ->margin(0)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();

        return 'data:image/png;base64,'.base64_encode($result->getString());
    }
}
