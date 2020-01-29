<?php

namespace App\Form;

use App\Entity\DateYear;
use App\Entity\Work;
use App\Form\DateYearType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkDatesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {    
        $work = $options['work'];
        $builder->add('dates', CollectionType::class, array(
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => DateYearType::class,
            'entry_options' => array(
                'work' => $work,
            ),
            'label' => 'Dates',
            'attr' => array(
                'group_class' => 'collection',
            ),
        ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Work::class,
            'work' => null,
        ));
    }
}
