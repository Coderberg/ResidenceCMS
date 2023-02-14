<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Repository\SettingsRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends Voter
{
    public const USE_HTML = 'USE_HTML';

    public function __construct(private readonly SettingsRepository $repository)
    {
    }

    protected function supports(string $attribute, $subject = null): bool
    {
        return self::USE_HTML === $attribute;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::USE_HTML => $this->canUseHtml($user),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canUseHtml(UserInterface $user): bool
    {
        if (\in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        $settings = $this->repository->findAllAsArray();

        if (!\array_key_exists('allow_html', $settings)) {
            return false;
        }

        return '1' === $settings['allow_html'];
    }
}
