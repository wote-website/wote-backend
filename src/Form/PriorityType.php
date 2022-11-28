<?php

namespace App\Form;

use App\Entity\Priority;
use App\Entity\Profile;
use App\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PriorityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value')
            //->add('priorizedWeightingsSum')
            // ->add('profile', EntityType::class, [
            //         'class' => Profile::class,
            //         'choice_label' => 'title'
            //     ])
            // ->add('theme', EntityType::class, [
            //         'class' => Theme::class,
            //         'choice_label' => 'title'
            //     ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Priority::class,
        ]);
    }
}
