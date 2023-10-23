<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContributionFilterType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('role', EntityType::class, [
            'label' => 'Role',
            'required' => false,
            'multiple' => true,
            'expanded' => true,
            'choice_label' => 'label',
            'class' => Role::class,
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'Name',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
