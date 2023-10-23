<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\DateCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateCategoryType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('name', null, [
            'label' => 'Name',
        ]);
        $builder->add('label', null, [
            'label' => 'Label',
        ]);
        $builder->add('description', null, [
            'label' => 'Description',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => DateCategory::class,
        ]);
    }
}
