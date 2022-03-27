<?php

declare(strict_types=1);

namespace App\Utils;

use Symfony\Component\Form\FormInterface;

final class UserFormDataSelector
{
    public function getEmailVerified(FormInterface $form): bool
    {
        return $form->get('email_verified')->getNormData();
    }

    public function getEmailVerifiedAt(FormInterface $form): ?\DateTime
    {
        return $this->getEmailVerified($form)
            ? new \DateTime('now')
            : null;
    }
}
