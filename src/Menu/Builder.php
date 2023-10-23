<?php

declare(strict_types=1);

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

    public function __construct(private FactoryInterface $factory, private AuthorizationCheckerInterface $authChecker, private TokenStorageInterface $tokenStorage) {}

    private function hasRole($role) : bool {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }

    /**
     * Build a menu content.
     *
     * @return ItemInterface
     */
    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);

        $search = $menu->addChild('search', [
            'uri' => '#',
            'label' => 'Search',
            'attributes' => [
                'class' => 'nav-item dropdown',
            ],
            'linkAttributes' => [
                'class' => 'nav-link dropdown-toggle',
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'id' => 'search-dropdown',
            ],
            'childrenAttributes' => [
                'class' => 'dropdown-menu text-small shadow',
                'aria-labelledby' => 'search-dropdown',
            ],
        ]);
        $search->addChild('search_advanced', [
            'label' => 'Advanced Search',
            'route' => 'work_search',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $search->addChild('search_person', [
            'label' => 'Person Search',
            'route' => 'person_search',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $search->addChild('search_publisher', [
            'label' => 'Publisher Search',
            'route' => 'publisher_search',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $search->addChild('search_subject', [
            'label' => 'Subject Search',
            'route' => 'subject_search',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);

        $browse = $menu->addChild('browse', [
            'uri' => '#',
            'label' => 'Browse',
            'attributes' => [
                'class' => 'nav-item dropdown',
            ],
            'linkAttributes' => [
                'class' => 'nav-link dropdown-toggle',
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'id' => 'browse-dropdown',
            ],
            'childrenAttributes' => [
                'class' => 'dropdown-menu text-small shadow',
                'aria-labelledby' => 'browse-dropdown',
            ],
        ]);

        $browse->addChild('date_category', [
            'label' => 'Date Categories',
            'route' => 'date_category_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $browse->addChild('genre', [
            'label' => 'Genres',
            'route' => 'genre_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $browse->addChild('person', [
            'label' => 'People',
            'route' => 'person_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $browse->addChild('place', [
            'label' => 'Places',
            'route' => 'place_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $browse->addChild('publisher', [
            'label' => 'Publishers',
            'route' => 'publisher_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $browse->addChild('role', [
            'label' => 'Roles',
            'route' => 'role_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $browse->addChild('subject', [
            'label' => 'Subjects',
            'route' => 'subject_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $browse->addChild('subject_source', [
            'label' => 'Subject Sources',
            'route' => 'subject_source_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $browse->addChild('work', [
            'label' => 'Works',
            'route' => 'work_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $browse->addChild('work_category', [
            'label' => 'Work Categories',
            'route' => 'work_category_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);

        if ($this->hasRole('ROLE_ADMIN')) {
            $browse->addChild('divider1', [
                'label' => '<hr class="dropdown-divider">',
                'extras' => [
                    'safe_label' => true,
                ],
            ]);

            $browse->addChild('contribution', [
                'label' => 'Contributions',
                'route' => 'contribution_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item',
                ],
            ]);
            $browse->addChild('date', [
                'label' => 'Dates',
                'route' => 'date_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item',
                ],
            ]);
        }

        return $menu;
    }
}
