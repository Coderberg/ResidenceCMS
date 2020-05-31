<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\User;
use App\Service\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class UserService extends AbstractService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(
        CsrfTokenManagerInterface $tokenManager,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        parent::__construct($tokenManager, $session);
        $this->em = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function create(User $user): void
    {
        $user = $this->setRoles($user);
        $user = $this->setEncodedPassword($user);
        $this->save($user);
        $this->clearCache('users_count');
        $this->addFlash('success', 'message.created');
    }

    public function update(User $user): void
    {
        $user = $this->setRoles($user);
        $user = $this->setEncodedPassword($user);
        $this->save($user);
        $this->addFlash('success', 'message.updated');
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
        $this->clearCache('users_count');
        $this->addFlash('success', 'message.deleted');
    }

    private function setRoles(User $user): User
    {
        if (\in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }

        return $user;
    }

    private function setEncodedPassword(User $user): User
    {
        $password = $user->getPassword();
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        return $user;
    }

    private function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}
