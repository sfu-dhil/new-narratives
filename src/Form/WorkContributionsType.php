<?php

namespace App\Form;

use App\Entity\DateYear;
use App\Entity\Work;
use App\Form\DateYearType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkContributionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {    
        $work = $options['work'];
        $builder->add('contributions', CollectionType::class, array(
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => ContributionType::class,
            'entry_options' => array(
                'work' => $work,
            ),
            'label' => 'Contributions',
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
