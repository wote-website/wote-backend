<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Classification;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('brand')
            ->add('barcode')
            ->add('reference')
            ->add('price')
            ->add('classification', EntityType::class, [
                'class' => Classification::class,
                'choice_label' => 'name'
            ])
            ->add('madeIn', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name'
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
