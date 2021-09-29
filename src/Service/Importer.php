<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

use App\Entity\Contribution;
use App\Entity\DateCategory;
use App\Entity\DateYear;
use App\Entity\Genre;
use App\Entity\Person;
use App\Entity\Publisher;
use App\Entity\Role;
use App\Entity\Subject;
use App\Entity\Work;
use App\Entity\WorkCategory;
use App\Repository\DateCategoryRepository;
use App\Repository\GenreRepository;
use App\Repository\PersonRepository;
use App\Repository\PublisherRepository;
use App\Repository\RoleRepository;
use App\Repository\SubjectRepository;
use App\Repository\WorkCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Monolog\Logger;
use Nines\MediaBundle\Entity\Link;
use Nines\UserBundle\Entity\User;
use Nines\UserBundle\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;

class Importer {
    private EntityManagerInterface $em;

    private Logger $logger;

    /**
     * If true, commit the import to the database. Set by the commit option
     * at the command line.
     */
    private bool $commit = false;

    private RoleRepository $roleRepository;

    private PersonRepository $personRepository;

    private DateCategoryRepository $dateCategoryRepository;

    private PublisherRepository $publisherRepository;

    private SubjectRepository $subjectRepository;

    private GenreRepository $genreRepository;

    private UserRepository $userRepository;

    private WorkCategoryRepository $workCategoryRepository;

    public function persist($entity) : void {
        if ( ! $this->commit) {
            return;
        }
        $this->em->persist($entity);
    }

    public function flush() : void {
        if ( ! $this->commit) {
            return;
        }
        $this->em->flush();
    }

    public function addCheckedBy($work, $name) : void {
        $user = $this->userRepository->findOneBy([
            'fullname' => $name,
        ]);
        if ( ! $user) {
            $user = new User();
            $user->setEmail($name . '@example.com');
            $user->setFullname($name);
            $user->setPassword(md5(uniqid()));
            $user->setActive(false);
            $user->setAffiliation('NEWN');
            $this->persist($user);
        }
        $work->addCheckedBy($user);
    }

    public function findPerson($name) : Person {
        $person = $this->personRepository->findOneBy(['fullName' => $name]);
        if ( ! $person) {
            $person = new Person();
            $person->setFullName($name);
            $this->persist($person);
        }

        return $person;
    }

    /**
     * @param mixed $name
     *
     * @throws Exception
     */
    public function findRole($name) : Role {
        $matches = [];
        if ( ! preg_match('/\[(\w+)\]/', $name, $matches)) {
            throw new Exception("Malformed role code '{$name}'.");
        }
        /** @var Role $role */
        $role = $this->roleRepository->findOneBy(['name' => $matches[1]]);
        if ( ! $role) {
            throw new Exception("Unknown role code '{$matches[1]}'.");
        }

        return $role;
    }

    /**
     * @throws Exception
     */
    public function addContribution(Work $work, string $name, string $role) : void {
        if ( ! $name || ! $role) {
            return;
        }
        $person = $this->findPerson($name);
        $role = $this->findRole($role);
        $contribution = new Contribution();
        $contribution->setPerson($person);
        $contribution->setRole($role);
        $contribution->setWork($work);
        $work->addContribution($contribution);
        $this->persist($contribution);
    }

    /**
     * @throws Exception
     */
    public function addDate(Work $work, string $date, string $label) : void {
        if ( ! $date) {
            return;
        }
        if ( ! $label) {
            $label = 'Date Issued';
        }
        /** @var DateCategory $category */
        $category = $this->dateCategoryRepository->findOneBy(['label' => $label]);
        if ( ! $category) {
            throw new Exception("Cannot find date category '{$category}'");
        }
        $dateYear = new DateYear();

        try {
            $dateYear->setValue($date);
        } catch (Exception $e) {
            throw new Exception("Malformed date '{$date}'");
        }
        $dateYear->setDateCategory($category);
        $dateYear->setWork($work);
        $work->addDate($dateYear);
        $this->persist($dateYear);
    }

    public function findPublisher(string $name) : ?Publisher {
        if ( ! $name) {
            return null;
        }
        $publisher = $this->publisherRepository->findOneBy(['name' => $name]);
        if ( ! $publisher) {
            $publisher = new Publisher();
            $publisher->setName($name);
            $this->persist($publisher);
        }

        return $publisher;
    }

    /**
     * @throws Exception
     */
    public function yesNo(string $str) : ?bool {
        if ( ! $str) {
            return null;
        }
        $s = mb_strtolower($str);

        switch ($s[0]) {
            case 'y': return true;

            case 'n': return false;

            default: throw new Exception("Malformed Yes/No field: '{$str}'");
        }
    }

    public function addSubjects(Work $work, ...$subjects) : void {
        foreach ($subjects as $label) {
            if ( ! $label) {
                continue;
            }
            /** @var Subject $subject */
            $subject = $this->subjectRepository->findOneBy(['label' => $label]);
            if ( ! $subject) {
                throw new Exception("Unknown subject '{$label}'");
            }
            $work->addSubject($subject);
        }
    }

    public function setGenre(Work $work, string $label) : void {
        if ( ! $label) {
            return;
        }
        /** @var Genre $genre */
        $genre = $this->genreRepository->findOneBy(['label' => $label]);
        if ( ! $genre) {
            throw new Exception("Unknown genre: '{$genre}'");
        }
        $work->setGenre($genre);
    }

    public function getUrls($string) : array {
        $matches = [];
        preg_match_all('{https?://[^\" \n]+}im', $string, $matches);

        return $matches[0];
    }

    /**
     * @throws Exception
     */
    public function setWorkCategory(Work $work, string $label) : void {
        if ( ! $label) {
            return;
        }
        if ('MS' === $label) {
            $label = 'Manuscript';
        }
        /** @var WorkCategory $category */
        $category = $this->workCategoryRepository->findOneBy(['label' => $label]);
        if ( ! $category) {
            throw new Exception("Unknown work category '{$label}'.");
        }
        $work->setWorkCategory($category);
    }

    /**
     * @throws Exception
     */
    public function import(array $row) : array {
        $links = [];

        $work = new Work();
        $work->setTitle($row[0]);
        // column 1 (numbered from 0) exists for the convenience of those entering data.
        $work->setVolume($row[2]);
        foreach ([3, 5, 7, 33, 35, 37, 42, 44, 46, 48, 50, 52, 54, 56, 58, 60, 62, 64] as $n) {
            $this->addContribution($work, $row[$n], $row[$n + 1]);
        }
        $this->addDate($work, $row[9], $row[10]);
        $work->setEdition($row[11]);
        $work->setPublicationPlace($row[12]);
        $work->setPublisher($this->findPublisher($row[13]));
        $work->setPhysicalDescription($row[14]);
        $work->setIllustrations($this->yesNo($row[15]));
        $work->setFrontispiece($this->yesNo($row[16]));
        $work->setTranslationDescription($row[17]);
        $work->setDedication($row[18]);
        // 19 & 20 seem unused.
        $wcUrls = $this->getUrls($row[21]);
        if (count($wcUrls)) {
            $work->setWorldcatUrl($wcUrls[0]);
        }
        if (count($wcUrls) > 1) {
            foreach (array_slice($wcUrls, 1) as $url) {
                $link = new Link();
                $link->setUrl($url);
                $links[] = $link;
            }
        }
        $this->addSubjects($work, $row[22], $row[23], $row[24]);
        // check columns A, AE /pamphlet/i and use that or $row[24] as appropriate
        if (preg_match('/pamphlet/', $row[0] . ' ' . $row[30])) {
            $this->setGenre($work, 'Pamphlet');
        } else {
            $this->setGenre($work, $row[24]);
        }
        $work->setTranscription($this->yesNo($row[26]));
        $work->setPhysicalLocations($row[27]);
        $work->setDigitalLocations($row[28]);
        $digitalUrls = $this->getUrls($row[29]);
        if (count($digitalUrls)) {
            $work->setDigitalUrl($digitalUrls[0]);
        }
        if (count($digitalUrls) > 1) {
            foreach (array_slice($digitalUrls, 1) as $url) {
                $link = new Link();
                $link->setUrl($url);
                $links[] = $link;
            }
        }
        $work->setNotes($row[30]);
        $work->setCitation($row[31]);
        $this->addCheckedBy($work, $row[32]);
        // Columns 33 to 38 are handled above.
        $this->setWorkCategory($work, $row[39]);
        // Column 40 seems to be unused.
        // Columns 41 to end are handled above.
        $this->persist($work);
        $this->flush();
        foreach ($links as $l) {
            $l->setEntity($work);
            $this->persist($l);
        }
        $this->flush();

        return [$work, $links];
    }

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em) : void {
        $this->em = $em;
    }

    /**
     * @required
     */
    public function setDateCategoryRepository(DateCategoryRepository $dateCategoryRepository) : void {
        $this->dateCategoryRepository = $dateCategoryRepository;
    }

    /**
     * @required
     */
    public function setPersonRepository(PersonRepository $personRepository) : void {
        $this->personRepository = $personRepository;
    }

    /**
     * @required
     */
    public function setRoleRepository(RoleRepository $roleRepository) : void {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @required
     */
    public function setPublisherRepository(PublisherRepository $publisherRepository) : void {
        $this->publisherRepository = $publisherRepository;
    }

    /**
     * @required
     */
    public function setGenreRepository(GenreRepository $genreRepository) : void {
        $this->genreRepository = $genreRepository;
    }

    /**
     * @required
     */
    public function setSubjectRepository(SubjectRepository $subjectRepository) : void {
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * @required
     */
    public function setUserRepository(UserRepository $userRepository) : void {
        $this->userRepository = $userRepository;
    }

    /**
     * @required
     */
    public function setWorkCategoryRepository(WorkCategoryRepository $workCategoryRepository) : void {
        $this->workCategoryRepository = $workCategoryRepository;
    }

    /**
     * @required
     */
    public function setLogger(LoggerInterface $logger) : void {
        $this->logger = $logger;
    }

    public function setCommit(bool $commit) : void {
        $this->commit = $commit;
    }
}
