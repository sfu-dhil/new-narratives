<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Work;
use Nines\MediaBundle\Form\LinkableType;
use Nines\MediaBundle\Form\Mapper\LinkableMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Config\Tradition;

class WorkType extends AbstractType {
    private ?LinkableMapper $mapper = null;

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('title', null, [
            'label' => 'Title',
        ]);
        $builder->add('workCategory', null, [
            'label' => 'Category',
        ]);

        $builder->add('edition', IntegerType::class, [
            'label' => 'Edition',
            'required' => false,
        ]);
        $builder->add('volume', IntegerType::class, [
            'label' => 'Volume',
            'required' => false,
        ]);
        $builder->add('languageCode', LanguageType::class, [
            'label' => 'Language',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'preferred_choices' => ['en'],
            'attr' => [
                'class' => 'select2-simple',
                'data-theme' => 'bootstrap-5',
            ],
        ]);
        $builder->add('tradition', EnumType::class, [
            'label' => 'Tradition',
            'required' => false,
            'multiple' => false,
            'class' => Tradition::class,
            'choice_label' => fn (?Tradition $tradition) : string => $tradition ? $tradition->label() : '',
        ]);
        $builder->add('publicationPlace', null, [
            'label' => 'Publication Place',
        ]);
        $builder->add('publisher', null, [
            'label' => 'Publisher',
        ]);

        $builder->add('physicalDescription', null, [
            'label' => 'Physical Description',
        ]);
        $builder->add('illustrations', ChoiceType::class, [
            'label' => 'Illustrations',
            'choices' => [
                'Unknown' => null,
                'Yes' => true,
                'No' => false,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);

        $builder->add('frontispiece', ChoiceType::class, [
            'label' => 'Frontispiece',
            'choices' => [
                'Unknown' => null,
                'Yes' => true,
                'No' => false,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);

        $builder->add('translationDescription', null, [
            'label' => 'Translation Description',
        ]);
        $builder->add('dedication', null, [
            'label' => 'Dedication',
        ]);
        $builder->add('worldcatUrl', null, [
            'label' => 'Worldcat Url',
        ]);
        $builder->add('subjects', null, [
            'label' => 'Subjects',
        ]);
        $builder->add('genre', null, [
            'label' => 'Genre',
        ]);

        $builder->add('transcription', ChoiceType::class, [
            'label' => 'Transcription',
            'choices' => [
                'Unknown' => null,
                'Yes' => true,
                'No' => false,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);

        $builder->add('physicalLocations', null, [
            'label' => 'Physical Locations',
        ]);
        $builder->add('digitalLocations', null, [
            'label' => 'Digital Locations',
        ]);
        $builder->add('digitalUrl', null, [
            'label' => 'Digital Url',
        ]);
        $builder->add('notes', null, [
            'label' => 'Notes',
        ]);
        LinkableType::add($builder, $options);
        $builder->setDataMapper($this->mapper);
    }

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setMapper(LinkableMapper $mapper) : void {
        $this->mapper = $mapper;
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Work::class,
        ]);
    }
}
