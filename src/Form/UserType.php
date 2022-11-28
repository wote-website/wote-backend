<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Country;
use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('fullName')
            ->add('phone')
            //->add('activeProfile')
            ->add('country', EntityType::class, [
               'class' => Country::class,
               'choice_label' => 'name'
                ])            
            ->add('activeProfile', EntityType::class, [
               'class' => Profile::class,
               'choice_label' => 'title',
               'placeholder' => 'choisir un profil'
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
