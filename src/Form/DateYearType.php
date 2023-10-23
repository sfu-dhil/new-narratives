<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\DateYear;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateYearType extends AbstractType {
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('dateCategory', null, [
            'label' => 'Category',
        ]);
        $builder->add('value', null, [
            'label' => 'Value',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => DateYear::class,
            'work' => null,
        ]);
    }
}
