<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Place form.
 */
class PlaceType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('name', TextType::class, [
            'label' => 'Name',
            'required' => true,
            'help' => 'Name of the city or township',
        ]);
        $builder->add('state', TextType::class, [
            'label' => 'State',
            'required' => false,
            'help' => 'Province or state name, unabbreviated',
        ]);
        $builder->add('country', TextType::class, [
            'label' => 'Country',
            'required' => true,
            'help' => 'Full country name',
        ]);
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
