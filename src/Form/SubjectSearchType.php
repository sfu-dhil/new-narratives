<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\SubjectSource;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectSearchType extends AbstractType
{
    public function getBlockPrefix() {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod('GET');
        $builder->add('q', TextType::class, [
            'label' => 'Search query',
            'required' => false,
        ]);
        $builder->add('source', EntityType::class, [
            'class' => SubjectSource::class,
            'choice_label' => 'label',
            'label' => 'Filter by source',
            'multiple' => true,
            'expanded' => true,
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => false,
        ]);
    }
}
