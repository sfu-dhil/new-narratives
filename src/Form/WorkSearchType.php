<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
class WorkSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod('get');
        $builder->add('title', TextType::class, [
            'required' => false,
        ]);
        $builder->add('category', EntityType::class, [
            'required' => false,
            'multiple' => true,
            'expanded' => true,
            'choice_label' => 'label',
            'class' => WorkCategory::class,
        ]);
        $builder->add('edition', IntegerType::class, [
            'required' => false,
        ]);
        $builder->add('volume', IntegerType::class, [
            'required' => false,
        ]);
        $builder->add('digitalEdition', ChoiceType::class, [
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
            'required' => false,
        ]);
        $builder->add('publisher', TextType::class, [
            'required' => false,
        ]);
        $builder->add('illustrations', ChoiceType::class, [
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => [
                'Yes' => 1,
                'No' => 0,
            ],
        ]);
        $builder->add('frontispiece', ChoiceType::class, [
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => [
                'Yes' => 1,
                'No' => 0,
            ],
        ]);
        $builder->add('dedication', TextType::class, [
            'required' => false,
        ]);
        $builder->add('subject', TextType::class, [
            'required' => false,
        ]);
        $builder->add('genre', EntityType::class, [
            'required' => false,
            'multiple' => true,
            'expanded' => true,
            'choice_label' => 'label',
            'class' => Genre::class,
        ]);
        $builder->add('transcription', ChoiceType::class, [
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
