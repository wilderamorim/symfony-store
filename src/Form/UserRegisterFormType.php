<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, SubmitType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterFormType extends AbstractType
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
            ->add('address', AddressFormType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
