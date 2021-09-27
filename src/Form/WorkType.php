<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use Nines\MediaBundle\Form\LinkableType;
use Nines\MediaBundle\Form\Mapper\LinkableMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkType extends AbstractType {
    private LinkableMapper $mapper;

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('title');
        $builder->add('workCategory', null, [
            'label' => 'Category',
        ]);

        $builder->add('edition', IntegerType::class, [
            'required' => false,
        ]);
        $builder->add('volume', IntegerType::class, [
            'required' => false,
        ]);
        $builder->add('publicationPlace');
        $builder->add('publisher');

        $builder->add('physicalDescription');
        $builder->add('illustrations', ChoiceType::class, [
            'choices' => [
                'Unknown' => null,
                'Yes' => true,
                'No' => false,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);

        $builder->add('frontispiece', ChoiceType::class, [
            'choices' => [
                'Unknown' => null,
                'Yes' => true,
                'No' => false,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);

        $builder->add('translationDescription');
        $builder->add('dedication');
        $builder->add('worldcatUrl');
        $builder->add('subjects');
        $builder->add('genre');

        $builder->add('transcription', ChoiceType::class, [
            'choices' => [
                'Unknown' => null,
                'Yes' => true,
                'No' => false,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);

        $builder->add('physicalLocations');
        $builder->add('digitalLocations');
        $builder->add('digitalUrl');
        $builder->add('notes');
        LinkableType::add($builder, $options);
        $builder->setDataMapper($this->mapper);
    }

    /**
     * @required
     */
    public function setMapper(LinkableMapper $mapper) : void {
        $this->mapper = $mapper;
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Work',
        ]);
    }
}
