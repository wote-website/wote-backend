<?php

namespace App\Form;

use App\Entity\Weighting;
use App\Entity\Profile;
use App\Entity\Criterion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class WeightingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value')
            //->add('creationDate')
            //->add('modificationDate')
            ->add('comment')
            // ->add('profile', EntityType::class, [
            //         'class' => Profile::class,
            //         'choice_label' => 'title'
            //     ])
            // ->add('criterion', EntityType::class, [
            //         'class' => Criterion::class,
            //         'choice_label' => 'designation'
            //     ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Weighting::class,
        ]);
    }
}
