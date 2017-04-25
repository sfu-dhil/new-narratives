<?php

namespace AppBundle\Transformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of HiddenEntityTransformer
 * 
 * Configure the form as a service:
 * services:
 *     app.form.date_year_type:
 *         class: AppBundle\Form\DateYearType
 *         arguments: [ "@doctrine.orm.entity_manager" ]
 *         tags:
 *             - { name: form.type }
 *
 * add the hidden field and transformer:
 *         $builder->add('work', HiddenType::class, array(
 *             'data' => $work,
 *             'data_class' => null,
 *         ));
 *         $builder->add('dateCategory');     
 *         $builder->add('value');     
 *         
 *         $builder->get('work')->addModelTransformer(new HiddenEntityTransformer($this->em, Work::class));
 *
 * @author michael
 */
class HiddenEntityTransformer implements DataTransformerInterface {
    
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var string
     */
    private $class;
    
    /**
     * Build and configure the transformer.
     * 
     * @param ObjectManager $em
     * @param type $class
     */
    public function __construct(ObjectManager $em, $class) {
        $this->em = $em;
        $this->class = $class;
    }
    
    /**
     * Transform an entity to a string.
     * 
     * @param object|null $entity
     * @return string
     */
    public function transform($entity) {
        if($entity === null) {
            return null;
        }
        return $entity->getId();
    }

    /**
     * Transforms  string into an entity.
     * 
     * @param string $value
     * @return null|object
     */
    public function reverseTransform($value) {
        if( ! $value) {
            return null;
        }
        
        return $this->em->find($this->class, $value);
    }

}
