<?php

namespace App\Form;

use App\Entity\Work;
use App\Transformer\HiddenEntityTransformer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateYearType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $em;
    
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {   
        $work = $options['work'];
        $builder->add('work', HiddenType::class, array(
            'data' => $work,
            'data_class' => null,
        ));
        $builder->add('dateCategory');     
        $builder->add('value');     
        
        $builder->get('work')->addModelTransformer(new HiddenEntityTransformer($this->em, Work::class));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\DateYear',
            'work' => null,
        ));
    }
}
