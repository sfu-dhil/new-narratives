<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Contribution;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ContributionType extends AbstractType {
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('role', null, [
            'label' => 'Role',
        ]);
        $builder->add('person', Select2EntityType::class, [
            'label' => 'Person',
            'class' => Person::class,
            'remote_route' => 'person_typeahead',
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'person_new',
                'add_label' => 'Add Person',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Contribution::class,
            'work' => null,
        ]);
    }
}
