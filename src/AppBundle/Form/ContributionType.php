<?php

namespace AppBundle\Form;

use AppBundle\Entity\Person;
use AppBundle\Entity\Work;
use AppBundle\Transformer\HiddenEntityTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContributionType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $em;
    
    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $work = $options['work'];        
        $builder->add('work', HiddenType::class, array(
            'data' => $work,
            'data_class' => null,
        ));     
        $builder->add('role');          
        
        $builder->add('person_name', TextType::class, array(
            'mapped' => false,
            'attr' => array(
                'class' => 'typeahead'
            )
        ));
        $builder->add('person', HiddenType::class, array(
            'attr' => array(
                'class' => 'contribution_person',
            )
        ));
        $builder->get('work')->addModelTransformer(
            new HiddenEntityTransformer($this->em, Work::class)
        );
        
        $builder->get('person')->addModelTransformer(
            new HiddenEntityTransformer($this->em, Person::class)
        );
        
        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event){
            $contribution = $event->getData();
            if($contribution === null) {
                return;
            }
            $builder = $event->getForm()->get('person_name');
            $builder->setData($contribution->getPerson()->getFullname());
        });
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contribution',
            'work' => null,  
        ));
    }
}
