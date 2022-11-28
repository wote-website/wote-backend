<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            //->add('creationDate')
            //->add('modificationDate')
            //->add('country')
            //->add('author')
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Brand::class,
        ]);
    }
}
