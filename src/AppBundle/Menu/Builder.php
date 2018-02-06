<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class to build some menus for navigation.
 */
class Builder implements ContainerAwareInterface {

    use ContainerAwareTrait;

    const CARET = ' ▾'; // U+25BE, black down-pointing small triangle.

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage) {
        $this->factory = $factory;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
    }

    private function hasRole($role) {
        if (!$this->tokenStorage->getToken()) {
            return false;
        }
        return $this->authChecker->isGranted($role);
    }
    
    /**
     * Build a menu content.
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'nav navbar-nav',
        ));
        
        $menu->addChild('home', array(
            'label' => 'Home',
            'route' => 'homepage',
        ));
        
        $search = $menu->addChild('search', array(
            'uri' => '#',
            'label' => 'Search ' . self::CARET,
        ));
        $search->setAttribute('dropdown', true);
        $search->setLinkAttribute('class', 'dropdown-toggle');
        $search->setLinkAttribute('data-toggle', 'dropdown');
        $search->setChildrenAttribute('class', 'dropdown-menu');
        
       $search->addChild('search_advanced', array(
            'label' => 'Advanced Search',
            'route' => 'work_search',
        ));
        $search->addChild('search_person', array(
            'label' => 'Person Search',
            'route' => 'person_search',
        ));
        $search->addChild('search_publisher', array(
            'label' => 'Publisher Search',
            'route' => 'publisher_search',
        ));
        $search->addChild('search_subject', array(
            'label' => 'Subject Search',
            'route' => 'subject_search',
        ));
        
        $browse = $menu->addChild('browse', array(
            'uri' => '#',
            'label' => 'Browse ' . self::CARET,
        ));
        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild('date_category', array(
            'label' => 'Date Categories',
            'route' => 'date_category_index',
        ));
        $browse->addChild('genre', array(
            'label' => 'Genres',
            'route' => 'genre_index',
        ));
        $browse->addChild('person', array(
            'label' => 'People',
            'route' => 'person_index',
        ));
        $browse->addChild('publisher', array(
            'label' => 'Publishers',
            'route' => 'publisher_index',
        ));
        $browse->addChild('role', array(
            'label' => 'Roles',
            'route' => 'role_index',
        ));
        $browse->addChild('subject', array(
            'label' => 'Subjects',
            'route' => 'subject_index',
        ));
        $browse->addChild('subject_source', array(
            'label' => 'Subject Sources',
            'route' => 'subject_source_index',
        ));
        $browse->addChild('work', array(
            'label' => 'Works',
            'route' => 'work_index',
        ));
        $browse->addChild('work_category', array(
            'label' => 'Work Categories',
            'route' => 'work_category_index',
        ));

        if ($this->hasRole('ROLE_CONTENT_ADMIN')) {
            $browse->addChild('divider', array(
                'label' => '',
            ));
            $browse['divider']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
            
            $browse->addChild('contribution', array(
                'label' => 'Contributions',
                'route' => 'contribution_index',
            ));
            $browse->addChild('date', array(
                'label' => 'Dates',
                'route' => 'date_index',
            ));
        }

        return $menu;
    }

}
