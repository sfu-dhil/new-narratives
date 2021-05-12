<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Work;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * WorkRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Work::class);
    }

    public function searchQuery($data) {
        $qb = $this->createQueryBuilder('e');
        $qb->distinct();
        if (isset($data['title'])) {
            $qb->andWhere('MATCH (e.title) AGAINST (:title BOOLEAN) > 0');
            $qb->setParameter('title', $data['title']);
        }
        if (isset($data['category']) && count($data['category']) > 0) {
            $qb->andWhere('e.workCategory IN (:categories)');
            $qb->setParameter('categories', $data['category']);
        }
        if (isset($data['edition']) && $data['edition']) {
            $qb->andWhere('e.edition = :edition');
            $qb->setParameter('edition', $data['edition']);
        }
        if (isset($data['volume']) && $data['volume']) {
            $qb->andWhere('e.volume = :volume');
            $qb->setParameter('volume', $data['volume']);
        }
        if (isset($data['digitalEdition']) && count($data['digitalEdition']) > 0) {
            $qb->andWhere('e.digitalUrl IS NOT NULL');
        }
        if (isset($data['contributor']) && count($data['contributor']) > 0) {
            foreach ($data['contributor'] as $idx => $filter) {
                if (null === $filter['name'] && 0 === $filter['role']->count()) {
                    continue;
                }
                $cAlias = 'c_' . $idx;
                $pAlias = 'p_' . $idx;
                $qb->innerJoin('e.contributions', $cAlias)
                    ->innerJoin("{$cAlias}.person", $pAlias)
                ;
                if (isset($filter['role'])) {
                    $qb->andWhere("{$cAlias}.role IN (:role_{$idx})");
                    $qb->setParameter(":role_{$idx}", $filter['role']);
                }
                if (isset($filter['name']) && $filter['name']) {
                    $qb->andWhere("MATCH ({$pAlias}.fullName) AGAINST (:name_{$idx} BOOLEAN) > 0");
                    $qb->setParameter("name_{$idx}", $filter['name']);
                }
            }
        }
        if (isset($data['publicationPlace'])) {
            $qb->andWhere('MATCH (e.publicationPlace) AGAINST (:publicationPlace BOOLEAN) > 0');
            $qb->setParameter('publicationPlace', $data['publicationPlace']);
        }
        if (isset($data['publisher'])) {
            $qb->innerJoin('e.publisher', 'p');
            $qb->andWhere('MATCH (p.name) AGAINST (:publisher BOOLEAN) > 0');
            $qb->setParameter('publisher', $data['publisher']);
        }
        if (isset($data['illustrations']) && count($data['illustrations']) > 0) {
            $qb->andWhere('e.illustrations IN (:illustrations)');
            $qb->setParameter('illustrations', $data['illustrations']);
        }
        if (isset($data['frontispiece']) && count($data['frontispiece']) > 0) {
            $qb->andWhere('e.frontispiece IN (:frontispiece)');
            $qb->setParameter('frontispiece', $data['frontispiece']);
        }
        if (isset($data['dedication'])) {
            $qb->andWhere('MATCH (e.dedication) AGAINST (:dedication BOOLEAN) > 0');
            $qb->setParameter('dedication', $data['dedication']);
        }
        if (isset($data['subject'])) {
            $qb->innerJoin('e.subjects', 's');
            $qb->andWhere('MATCH (s.label) AGAINST (:subject BOOLEAN) > 0');
            $qb->setParameter('subject', $data['subject']);
        }
        if (isset($data['genre']) && count($data['genre']) > 0) {
            $qb->andWhere('e.genre IN (:genres)');
            $qb->setParameter('genres', $data['genre']);
        }
        if (isset($data['transcription']) && count($data['transcription']) > 0) {
            $qb->andWhere('e.transcription IN (:transcription)');
            $qb->setParameter('transcription', $data['transcription']);
        }
        $qb->orderBy('e.title');

        return $qb->getQuery();
    }
}
