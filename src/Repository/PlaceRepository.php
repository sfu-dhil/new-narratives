<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Place find($id, $lockMode = null, $lockVersion = null)
 * @method null|Place findOneBy(array $criteria, array $orderBy = null)
 * @method Place[] findAll()
 * @method Place[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Place::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('place')
            ->orderBy('place.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Collection|Place[]
     */
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('place');
        $qb->andWhere('place.name LIKE :q');
        $qb->orderBy('place.name', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $q
     *
     * @return Query
     */
    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('place');
        $qb->addSelect('MATCH (place.name) AGAINST(:q BOOLEAN) as HIDDEN score');
        $qb->andHaving('score > 0');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
