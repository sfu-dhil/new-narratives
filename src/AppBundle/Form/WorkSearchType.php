<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form;

use AppBundle\Entity\Genre;
use AppBundle\Entity\WorkCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of WorkSearchType
 *
 * @author michael
 */
class WorkSearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setMethod('get');
        $builder->add('title', TextType::class, array(
            'required' => false,
        ));
        $builder->add('category', EntityType::class, array(
            'required' => false,
            'multiple' => true,
            'expanded' => true,
            'choice_label' => 'label',
            'class' => WorkCategory::class,
        ));
        $builder->add('edition', IntegerType::class, array(
            'required' => false,
        ));
        $builder->add('volume', IntegerType::class, array(
            'required' => false,
        ));
        $builder->add('contributor', CollectionType::class, array(
            'label' => 'Contributors',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => ContributionFilterType::class,
            'entry_options' => array(
                'label' => false,
            ),
            'attr' => array(
                'class' => 'collection collection-complex'
            ),
        ));
        $builder->add('publicationPlace', TextType::class, array(
            'required' => false,
        ));
        $builder->add('publisher', TextType::class, array(
            'required' => false,
        ));
        $builder->add('illustrations', ChoiceType::class, array(
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => array(
                'Yes' => 1,
                'No' => 0,
            ),
        ));
        $builder->add('frontispiece', ChoiceType::class, array(
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => array(
                'Yes' => 1,
                'No' => 0,
            ),
        ));
        $builder->add('dedication', TextType::class, array(
            'required' => false,
        ));
        $builder->add('subject', TextType::class, array(
            'required' => false,
        ));
        $builder->add('genre', EntityType::class, array(
            'required' => false,
            'multiple' => true,
            'expanded' => true,
            'choice_label' => 'label',
            'class' => Genre::class,
        ));
        $builder->add('transcription', ChoiceType::class, array(
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => array(
                'Yes' => 1,
                'No' => 0,
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => null,
            'csrf_protection' => false,
        ));
    }

}
