<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Property;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class PropertyVoter extends Voter
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     */
    protected function supports($attribute, $subject): bool
    {
        return \in_array($attribute, ['PROPERTY_EDIT', 'PROPERTY_VIEW'], true)
            && $subject instanceof Property;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var Property $property */
        $property = $subject;

        switch ($attribute) {
            case 'PROPERTY_VIEW':
                return $this->canView($property, $token);

            case 'PROPERTY_EDIT':
                return $this->canEdit($property, $token);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canView(Property $property, TokenInterface $token): bool
    {
        // if they can edit, they can view
        if ($this->canEdit($property, $token)) {
            return true;
        }

        return $property->isPublished();
    }

    private function canEdit(Property $property, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        } elseif ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $user === $property->getAuthor();
    }
}
