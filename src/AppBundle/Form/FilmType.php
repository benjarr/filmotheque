<?php

namespace AppBundle\Form;

use AppBundle\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('categorie', EntityType::class, array(
                'class'        => 'AppBundle:Categorie',
                'choice_label' => 'nom',
                'required'     => false,
            ))
            ->add('acteurs', EntityType::class, array(
                'class'        => 'AppBundle:Acteur',
                'choice_label' => 'PrenomNom',
                'expanded'     => true,
                'multiple'     => true,
                'required'     => false,
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Film'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_film';
    }


}
