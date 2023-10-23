<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\SubjectSource;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectSearchType extends AbstractType {
    public function getBlockPrefix() {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod(\Symfony\Component\HttpFoundation\Request::METHOD_GET);
        $builder->add('q', TextType::class, [
            'label' => 'Search query',
            'required' => false,
        ]);
        $builder->add('source', EntityType::class, [
            'label' => 'Filter by source',
            'class' => SubjectSource::class,
            'choice_label' => 'label',
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
