<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Menu;

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

    public const CARET = ' ▾'; // U+25BE, black down-pointing small triangle.

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
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }

    /**
     * Build a menu content.
     *
     * @param FactoryInterface $factory
     *
     * @return ItemInterface
     */
    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);

        $menu->addChild('home', [
            'label' => 'Home',
            'route' => 'homepage',
        ]);

        $search = $menu->addChild('search', [
            'uri' => '#',
            'label' => 'Search ' . self::CARET,
        ]);
        $search->setAttribute('dropdown', true);
        $search->setLinkAttribute('class', 'dropdown-toggle');
        $search->setLinkAttribute('data-toggle', 'dropdown');
        $search->setChildrenAttribute('class', 'dropdown-menu');

        $search->addChild('search_advanced', [
            'label' => 'Advanced Search',
            'route' => 'work_search',
        ]);
        $search->addChild('search_person', [
            'label' => 'Person Search',
            'route' => 'person_search',
        ]);
        $search->addChild('search_publisher', [
            'label' => 'Publisher Search',
            'route' => 'publisher_search',
        ]);
        $search->addChild('search_subject', [
            'label' => 'Subject Search',
            'route' => 'subject_search',
        ]);

        $browse = $menu->addChild('browse', [
            'uri' => '#',
            'label' => 'Browse ' . self::CARET,
        ]);
        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild('date_category', [
            'label' => 'Date Categories',
            'route' => 'date_category_index',
        ]);
        $browse->addChild('genre', [
            'label' => 'Genres',
            'route' => 'genre_index',
        ]);
        $browse->addChild('person', [
            'label' => 'People',
            'route' => 'person_index',
        ]);
        $browse->addChild('publisher', [
            'label' => 'Publishers',
            'route' => 'publisher_index',
        ]);
        $browse->addChild('role', [
            'label' => 'Roles',
            'route' => 'role_index',
        ]);
        $browse->addChild('subject', [
            'label' => 'Subjects',
            'route' => 'subject_index',
        ]);
        $browse->addChild('subject_source', [
            'label' => 'Subject Sources',
            'route' => 'subject_source_index',
        ]);
        $browse->addChild('work', [
            'label' => 'Works',
            'route' => 'work_index',
        ]);
        $browse->addChild('work_category', [
            'label' => 'Work Categories',
            'route' => 'work_category_index',
        ]);

        if ($this->hasRole('ROLE_CONTENT_ADMIN')) {
            $browse->addChild('divider', [
                'label' => '',
            ]);
            $browse['divider']->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);

            $browse->addChild('contribution', [
                'label' => 'Contributions',
                'route' => 'contribution_index',
            ]);
            $browse->addChild('date', [
                'label' => 'Dates',
                'route' => 'date_index',
            ]);
        }

        return $menu;
    }
}
