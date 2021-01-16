<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', null, [
                'label' => 'Rua'
            ])
            ->add('number', null, [
                'label' => 'Número'
            ])
            ->add('neighborhood', null, [
                'label' => 'Bairro'
            ])
            ->add('city', null, [
                'label' => 'Cidade'
            ])
            ->add('state', null, [
                'label' => 'Estado'
            ])
            ->add('zipCode', null, [
                'label' => 'CEP'
            ])
            ->add('Cadastrar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-lg btn-success'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
