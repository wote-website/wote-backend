<?php

namespace App\Form;

use App\Entity\ProductScale;
use App\Entity\User;
use App\Entity\Profile;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductScaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // ->add('user', EntityType::class, [
            //         'class' => User::class,
            //         'choice_label' => 'email'
            //     ])
            ->add('product', EntityType::class, [
                    'class' => Product::class,
                    'choice_label' => 'name'
                ])
            // ->add('profile', EntityType::class, [
            //         'class' => Profile::class,
            //         'choice_label' => 'title'
            //     ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Visible dans le comparateur' => 'ACTIVE'
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Statut'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductScale::class,
        ]);
    }
}
