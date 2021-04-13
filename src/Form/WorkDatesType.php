<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Work;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkDatesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $work = $options['work'];
        $builder->add('dates', CollectionType::class, [
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => DateYearType::class,
            'entry_options' => [
                'work' => $work,
            ],
            'label' => 'Dates',
            'attr' => [
                'group_class' => 'collection',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Work::class,
            'work' => null,
        ]);
    }
}
