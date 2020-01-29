<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {    
        $builder->add('title');     
        $builder->add('workCategory', null, array(
            'label' => 'Category',
        ));     
        
        $builder->add('edition', IntegerType::class, array(
            'required' => false
        ));     
        $builder->add('volume', IntegerType::class, array(
            'required' => false
        ));          
        $builder->add('publicationPlace');     
        $builder->add('publisher');     
        
        $builder->add('physicalDescription');     
        $builder->add('illustrations', ChoiceType::class, array(
            'choices' => array(
                'Unknown' => null,
                'Yes' => true,
                'No' => false,
            ),
            'expanded' => true,
            'multiple' => false,
        ));     
        
        $builder->add('frontispiece', ChoiceType::class, array(
            'choices' => array(
                'Unknown' => null,
                'Yes' => true,
                'No' => false,
            ),
            'expanded' => true,
            'multiple' => false,
        ));         
        
        $builder->add('translationDescription');     
        $builder->add('dedication');     
        $builder->add('worldcatUrl');     
        $builder->add('subjects');         
        $builder->add('genre');     
        
        $builder->add('transcription', ChoiceType::class, array(
            'choices' => array(
                'Unknown' => null,
                'Yes' => true,
                'No' => false,
            ),
            'expanded' => true,
            'multiple' => false,
        ));     
        
        $builder->add('physicalLocations');     
        $builder->add('digitalLocations');     
        $builder->add('digitalUrl');     
        
        $builder->add('notes');     
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Work'
        ));
    }
}
