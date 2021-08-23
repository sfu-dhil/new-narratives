<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Person;
use App\Entity\Place;
use Nines\MediaBundle\Form\LinkableType;
use Nines\MediaBundle\Form\Mapper\LinkableMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Person form.
 */
class PersonType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('fullName', TextType::class, [
            'label' => 'Full Name',
            'required' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);

        $builder->add('birthDate', TextType::class, [
            'label' => 'Birth Date',
            'required' => false,
            'attr' => [
                'help_block' => 'Enter the date as YYYY-MM-DD or the year as YYYY.',
                'placeholder' => 'yyyy-mm-dd',
            ],
        ]);

        $builder->add('birthPlace', Select2EntityType::class, [
            'label' => 'Birth Place',
            'class' => Place::class,
            'remote_route' => 'place_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'place_new_popup',
                'add_label' => 'Add Place',
            ],
        ]);

        $builder->add('residences', Select2EntityType::class, [
            'label' => 'Residences',
            'class' => Place::class,
            'remote_route' => 'place_typeahead',
            'allow_clear' => true,
            'multiple' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'place_new_popup',
                'add_label' => 'Add Place',
            ],
        ]);

        $builder->add('deathDate', TextType::class, [
            'label' => 'Death Date',
            'required' => false,
            'attr' => [
                'help_block' => 'Enter the date as YYYY-MM-DD or the year as YYYY.',
                'placeholder' => 'yyyy-mm-dd',
            ],
        ]);

        $builder->add('deathPlace', Select2EntityType::class, [
            'label' => 'Death Place',
            'class' => Place::class,
            'remote_route' => 'place_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'place_new_popup',
                'add_label' => 'Add Place',
            ],
        ]);
        $builder->add('biography', TextareaType::class, [
            'label' => 'Biography',
            'required' => false,
            'attr' => [
                'help_block' => '',
                'class' => 'tinymce',
            ],
        ]);
        LinkableType::add($builder, $options);
        $builder->setDataMapper($this->mapper);
    }

    /**
     * @param LinkableMapper $mapper
     * @required
     */
    public function setMapper(LinkableMapper $mapper) {
        $this->mapper = $mapper;
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
