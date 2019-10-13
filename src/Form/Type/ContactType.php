<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from_name', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.name',
            ])
            ->add('from_email', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.enter_email',
                ],
                'label' => 'label.email',
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '7',
                ],
                'label' => 'label.message',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
