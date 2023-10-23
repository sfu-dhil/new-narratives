<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Person;
use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Person find($id, $lockMode = null, $lockVersion = null)
 * @method null|Person findOneBy(array $criteria, array $orderBy = null)
 * @method Person[] findAll()
 * @method Person[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Person::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('person')
            ->orderBy('person.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Collection|Person[]
     */
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('person');
        $qb->andWhere('person.fullName LIKE :q');
        $qb->orderBy('person.fullName', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $q
     *
     * @return Query
     */
    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('person');
        $qb->addSelect('MATCH (person.fullName) AGAINST(:q BOOLEAN) as HIDDEN score');
        $qb->andHaving('score > 0');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }

    /**
     * @return Query
     */
    public function countByRole(Role $role) {
        $qb = $this->createQueryBuilder('person');
        $qb->select('person as p, count(1) as c');
        $qb->innerJoin('person.contributions', 'contribution');
        $qb->where('contribution.role = :role');
        $qb->groupBy('person');
        $qb->setParameter('role', $role);
        $qb->orderBy('c', 'DESC');

        return $qb->getQuery();
    }
}
