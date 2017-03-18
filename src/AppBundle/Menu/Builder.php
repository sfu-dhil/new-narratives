<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class to build some menus for navigation.
 */
class Builder implements ContainerAwareInterface {

    use ContainerAwareTrait;

    /**
     * Build a menu for blog posts.
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function navMenu(FactoryInterface $factory, array $options) {
        $em = $this->container->get('doctrine')->getManager();

        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'dropdown-menu',
        ));
        $menu->setAttribute('dropdown', true);
        
        $menu->addChild('contribution', array(
            'label' => 'Contributions',
            'route' => 'contribution_index',
        ));
        $menu->addChild('date', array(
            'label' => 'Dates',
            'route' => 'date_index',
        ));
        $menu->addChild('date_category', array(
            'label' => 'Date Categories',
            'route' => 'date_category_index',
        ));
        $menu->addChild('genre', array(
            'label' => 'Genres',
            'route' => 'genre_index',
        ));
        $menu->addChild('person', array(
            'label' => 'People',
            'route' => 'person_index',
        ));
        $menu->addChild('publisher', array(
            'label' => 'Publishers',
            'route' => 'publisher_index',
        ));
        $menu->addChild('role', array(
            'label' => 'Roles',
            'route' => 'role_index',
        ));
        $menu->addChild('subject', array(
            'label' => 'Subjects',
            'route' => 'subject_index',
        ));
        $menu->addChild('subject_source', array(
            'label' => 'Subject Sources',
            'route' => 'subject_source_index',
        ));
        $menu->addChild('work', array(
            'label' => 'Works',
            'route' => 'work_index',
        ));
        $menu->addChild('work_category', array(
            'label' => 'Work Categories',
            'route' => 'work_category_index',
        ));
        
        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $menu->addChild('divider', array(
                'label' => '',
            ));
            $menu['divider']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
        }

        return $menu;
    }

}
