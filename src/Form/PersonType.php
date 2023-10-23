<?php

declare(strict_types=1);

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
    private ?LinkableMapper $mapper = null;

    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('fullName', TextType::class, [
            'label' => 'Full Name',
            'required' => true,
        ]);

        $builder->add('birthDate', TextType::class, [
            'label' => 'Birth Date',
            'required' => false,
            'help' => 'Enter the date as YYYY-MM-DD or the year as YYYY.',
            'attr' => [
                'placeholder' => 'yyyy-mm-dd',
            ],
        ]);

        $builder->add('birthPlace', Select2EntityType::class, [
            'label' => 'Birth Place',
            'class' => Place::class,
            'remote_route' => 'place_typeahead',
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'place_new',
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
                'add_path' => 'place_new',
                'add_label' => 'Add Place',
            ],
        ]);

        $builder->add('deathDate', TextType::class, [
            'label' => 'Death Date',
            'required' => false,
            'help' => 'Enter the date as YYYY-MM-DD or the year as YYYY.',
            'attr' => [
                'placeholder' => 'yyyy-mm-dd',
            ],
        ]);

        $builder->add('deathPlace', Select2EntityType::class, [
            'label' => 'Death Place',
            'class' => Place::class,
            'remote_route' => 'place_typeahead',
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'place_new',
                'add_label' => 'Add Place',
            ],
        ]);
        $builder->add('biography', TextareaType::class, [
            'label' => 'Biography',
            'required' => false,
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        LinkableType::add($builder, $options);
        $builder->setDataMapper($this->mapper);
    }

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setMapper(LinkableMapper $mapper) : void {
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
