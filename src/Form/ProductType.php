<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{FileType, SubmitType, TextType};
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{All, Image};

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nome'
            ])
            ->add('description', null, [
                'label' => 'Descrição',
                'help' => 'Tamanho máximo de 160 caracteres',
            ])
            ->add('body', null, [
                'label' => 'Corpo',
            ])
            ->add('price', TextType::class, [
                'label' => 'Preço',
                'attr' => ['class' => 'price'],
            ])
            ->add('photos', FileType::class, [
                'label' => 'Fotos',
                'mapped' => false,
                'multiple' => true,
                'constraints' => [
                    new All(
                        [
                            'constraints' => [
                                new Image([
                                    'mimeTypesMessage' => 'Este arquivo não é uma imagem válida.'
                                ])
                            ]
                        ]
                    )
                ]
            ])
            ->add('category', null, [
                'label' => 'Categoria',
                'expanded' => true,
            ])
            ->add('Salvar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-lg btn-success'],
            ])
        ;

        $builder->get('price')->addModelTransformer(new CallbackTransformer(
            function ($price) {
                return number_format(($price / 100), 2, ',', '.');
            },
            function ($price) {
                $price = str_replace(['.', ','], [null, '.'], $price);
                return (int)ceil($price * 100);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
