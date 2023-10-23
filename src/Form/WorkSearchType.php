<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Genre;
use App\Entity\WorkCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of WorkSearchType.
 *
 * @author michael
 */
class WorkSearchType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod('get');
        $builder->add('title', TextType::class, [
            'label' => 'Title',
            'required' => false,
        ]);
        $builder->add('category', EntityType::class, [
            'label' => 'Category',
            'required' => false,
            'multiple' => true,
            'expanded' => true,
            'choice_label' => 'label',
            'class' => WorkCategory::class,
        ]);
        $builder->add('edition', IntegerType::class, [
            'label' => 'Edition',
            'required' => false,
        ]);
        $builder->add('volume', IntegerType::class, [
            'label' => 'Volume',
            'required' => false,
        ]);
        $builder->add('digitalEdition', ChoiceType::class, [
            'label' => 'Digital Edition',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => [
                'Yes' => 1,
            ],
        ]);
        $builder->add('contributor', CollectionType::class, [
            'label' => 'Contributors',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => ContributionFilterType::class,
            'entry_options' => [
                'label' => false,
            ],
            'attr' => [
                'class' => 'collection collection-complex',
            ],
        ]);
        $builder->add('publicationPlace', TextType::class, [
            'label' => 'Publication Place',
            'required' => false,
        ]);
        $builder->add('publisher', TextType::class, [
            'label' => 'Publisher',
            'required' => false,
        ]);
        $builder->add('illustrations', ChoiceType::class, [
            'label' => 'Illustrations',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => [
                'Yes' => 1,
                'No' => 0,
            ],
        ]);
        $builder->add('frontispiece', ChoiceType::class, [
            'label' => 'Frontispiece',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => [
                'Yes' => 1,
                'No' => 0,
            ],
        ]);
        $builder->add('dedication', TextType::class, [
            'label' => 'Dedication',
            'required' => false,
        ]);
        $builder->add('subject', TextType::class, [
            'label' => 'Subject',
            'required' => false,
        ]);
        $builder->add('genre', EntityType::class, [
            'label' => 'Genre',
            'required' => false,
            'multiple' => true,
            'expanded' => true,
            'choice_label' => 'label',
            'class' => Genre::class,
        ]);
        $builder->add('transcription', ChoiceType::class, [
            'label' => 'Transcription',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => [
                'Yes' => 1,
                'No' => 0,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => false,
        ]);
    }
}
