<?php

namespace AppBundle\Form;

use AppBundle\Entity\Work;
use AppBundle\Transformer\HiddenEntityTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
        $builder->add('person');         
        
        $builder->get('work')->addModelTransformer(
            new HiddenEntityTransformer($this->em, Work::class)
        );
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
