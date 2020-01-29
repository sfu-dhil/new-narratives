<?php

namespace App\Form;

use App\Entity\SubjectSource;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectSearchType extends AbstractType {

    public function getBlockPrefix() {
        return '';
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod('GET');
        $builder->add('q', TextType::class, array(
            'label' => 'Search query',
            'required' => false,
        ));
        $builder->add('source', EntityType::class, array(
            'class' => SubjectSource::class,
            'choice_label' => 'label',
            'label' => 'Filter by source',
            'multiple' => true,
            'expanded' => true,
            'required' => false,
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => null,
            'csrf_protection'   => false,
        ));
    }

}
