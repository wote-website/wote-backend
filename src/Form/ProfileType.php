<?php

namespace App\Form;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Doctrine\ORM\EntityRepository;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('isPublic', ChoiceType::class, [
                'choices' => [
                    'oui' => true,
                    'non' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Ce profile est publique'
            ])
            //->add('creationDate')
            //->add('modificationDate')
            //->add('author', EntityType::class, [
            //        'class' => User::class,
                //     'choice_label' => 'email'
                // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
