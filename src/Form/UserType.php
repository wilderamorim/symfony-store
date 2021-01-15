<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{ChoiceType, PasswordType, SubmitType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'label' => 'Primeiro Nome'
            ])
            ->add('lastName', null, [
                'label' => 'Último Nome'
            ])
            ->add('email', null, [
                'label' => 'E-mail'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Senha',
                'mapped' => false
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Funções',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Administrador'     => 'ROLE_ADMIN',
                    'Gerente'           => 'ROLE_MANAGER',
                    'Usuário'           => 'ROLE_USER'
                ]
            ])
            ->add('Salvar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-lg btn-success'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
