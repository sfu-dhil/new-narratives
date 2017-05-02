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
     * Build a search menu.
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function searchMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'dropdown-menu',
        ));
        $menu->setAttribute('dropdown', true);

        $menu->addChild('search_advanced', array(
            'label' => 'Advanced Search',
            'route' => 'work_search',
        ));
        $menu->addChild('search_person', array(
            'label' => 'Person Search',
            'route' => 'person_search',
        ));
        $menu->addChild('search_publisher', array(
            'label' => 'Publisher Search',
            'route' => 'publisher_search',
        ));
        $menu->addChild('search_subject', array(
            'label' => 'Subject Search',
            'route' => 'subject_search',
        ));
        return $menu;
    }
    
    /**
     * Build a menu content.
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function navMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'dropdown-menu',
        ));
        $menu->setAttribute('dropdown', true);

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

        if ($this->container->get('security.token_storage')->getToken() && $this->container->get('security.authorization_checker')->isGranted('ROLE_CONTENT_ADMIN')) {
            $menu->addChild('divider', array(
                'label' => '',
            ));
            $menu['divider']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
            
            $menu->addChild('contribution', array(
                'label' => 'Contributions',
                'route' => 'contribution_index',
            ));
            $menu->addChild('date', array(
                'label' => 'Dates',
                'route' => 'date_index',
            ));
        }

        return $menu;
    }

}
