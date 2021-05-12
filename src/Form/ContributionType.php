<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Person;
use App\Entity\Work;
use App\Transformer\HiddenEntityTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContributionType extends AbstractType {
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $work = $options['work'];
        $builder->add('work', HiddenType::class, [
            'data' => $work,
            'data_class' => null,
        ]);
        $builder->add('role');

        $builder->add('person_name', TextType::class, [
            'mapped' => false,
            'attr' => [
                'class' => 'typeahead',
            ],
        ]);
        $builder->add('person', HiddenType::class, [
            'attr' => [
                'class' => 'contribution_person',
            ],
        ]);
        $builder->get('work')->addModelTransformer(
            new HiddenEntityTransformer($this->em, Work::class)
        );

        $builder->get('person')->addModelTransformer(
            new HiddenEntityTransformer($this->em, Person::class)
        );

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) : void {
            $contribution = $event->getData();
            if (null === $contribution) {
                return;
            }
            $builder = $event->getForm()->get('person_name');
            $builder->setData($contribution->getPerson()->getFullname());
        });
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Contribution',
            'work' => null,
        ]);
    }
}
