<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{FileType, SubmitType, TextType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nome'
            ])
            ->add('slug')
            ->add('description', null, [
                'label' => 'Descrição',
                'help' => 'Tamanho máximo de 160 caracteres',
            ])
            ->add('body', null, [
                'label' => 'Corpo',
            ])
            ->add('price', TextType::class, [
                'label' => 'Preço',
            ])
            ->add('photos', FileType::class, [
                'label' => 'Fotos',
                'mapped' => false,
                'multiple' => true
            ])
            ->add('category', null, [
                'label' => 'Categoria',
                'expanded' => true,
            ])
            ->add('Salvar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-lg btn-success'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
