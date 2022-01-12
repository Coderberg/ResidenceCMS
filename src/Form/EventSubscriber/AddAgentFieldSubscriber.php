<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Security;

class AddAgentFieldSubscriber implements EventSubscriberInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [FormEvents::POST_SET_DATA => 'onAgentNotSelected'];
    }

    public function onAgentNotSelected(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();
        $agent = $data->getAuthor();

        if (!$agent) {
            $form->add('author', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'profile.full_name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.agent',
                'data' => $this->security->getUser(),
            ]);
        }
    }
}
