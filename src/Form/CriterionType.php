<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Criterion;
use App\Entity\Theme;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CriterionType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('designation')
            ->add('description')
            ->add('proposal')
            ->add('code')
            ->add('unit')
            ->add('formula')
            ->add('source')
            ->add('sourceLink')
            ->add('sourceDescription')
            ->add('reportOriginalLink')
            ->add('status')
            ->add('theme', EntityType::class, [
                    'class' => Theme::class,
                    'choice_label' => 'title'
                ])
            ->add('country', EntityType::class, [
                    'class' => Country::class,
                    'query_builder' => function (EntityRepository $er){
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    },
                    'choice_label' => function ($country){
                        return $country->getName().' ('.$country->getAlpha3().')';
                    },
                    'required' => FALSE,
                ])
            ->add('logoFile', FileType::class, [
                'required' => false,
            ])
            ->add('logoOriginalLink')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Criterion::class,
        ]);
    }
}
