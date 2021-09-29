<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Service;

use App\DataFixtures\ContributionFixtures;
use App\DataFixtures\DateCategoryFixtures;
use App\DataFixtures\PersonFixtures;
use App\DataFixtures\PublisherFixtures;
use App\DataFixtures\RoleFixtures;
use App\DataFixtures\SubjectFixtures;
use App\DataFixtures\WorkCategoryFixtures;
use App\DataFixtures\WorkFixtures;
use App\Entity\Work;
use App\Service\Importer;
use Doctrine\ORM\UnitOfWork;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\BaseCase;

class ImporterTest extends BaseCase {
    private ?Importer $importer = null;

    protected function fixtures() : array {
        return [
            UserFixtures::class,
            PersonFixtures::class,
            RoleFixtures::class,
            WorkFixtures::class,
            ContributionFixtures::class,
            DateCategoryFixtures::class,
            PublisherFixtures::class,
            SubjectFixtures::class,
            WorkCategoryFixtures::class,
        ];
    }

    protected function state($entity) : int {
        return $this->entityManager->getUnitOfWork()->getEntityState($entity);
    }

    public function testSetUp() : void {
        $this->assertInstanceOf(Importer::class, $this->importer);
    }

    public function testAddCheckedByNew() : void {
        $work = new Work();
        $this->importer->addCheckedBy($work, 'abc');
        $user = $work->getCheckedBy()[0];
        $this->assertNotNull($user);
        $this->assertSame('abc@example.com', $user->getEmail());
        $this->assertSame(UnitOfWork::STATE_NEW, $this->state($user));
    }

    public function testAddCheckedByExisting() : void {
        $work = new Work();
        $this->importer->addCheckedBy($work, 'Unprivileged user');
        $user = $work->getCheckedBy()[0];
        $this->assertNotNull($user);
        $this->assertSame(UserFixtures::USER['username'], $user->getEmail());
        $this->assertSame(UnitOfWork::STATE_MANAGED, $this->state($user));
    }

    public function testFindPersonNew() : void {
        $person = $this->importer->findPerson('Marge');
        $this->assertNotNull($person);
        $this->assertSame('Marge', $person->getFullName());
        $this->assertSame(UnitOfWork::STATE_NEW, $this->state($person));
    }

    public function testFindPersonExisting() : void {
        $person = $this->importer->findPerson('FullName 1');
        $this->assertNotNull($person);
        $this->assertSame('FullName 1', $person->getFullName());
        $this->assertSame(UnitOfWork::STATE_MANAGED, $this->state($person));
    }

    public function testFindRoleBad() : void {
        $this->expectExceptionMessageMatches('/Malformed role code/');
        $role = $this->importer->findRole('New Role');
    }

    public function testFindRoleUnknown() : void {
        $this->expectExceptionMessageMatches('/Unknown role code/');
        $role = $this->importer->findRole('[aaa]');
    }

    public function testFindRoleExisting() : void {
        $role = $this->importer->findRole('[role_a]');
        $this->assertNotNull($role);
        $this->assertSame('Role 0', $role->getLabel());
        $this->assertSame(UnitOfWork::STATE_MANAGED, $this->state($role));
    }

    public function testAddNullContribution() : void {
        /** @var Work $work */
        $work = $this->getReference('work.1');
        $count = count($work->getContributions());
        $this->importer->addContribution($work, '', '[aut]');
        $this->assertSame($count, count($work->getContributions()));
        $this->importer->addContribution($work, 'Felicia', '');
        $this->assertSame($count, count($work->getContributions()));
    }

    public function testAddContribution() : void {
        /** @var Work $work */
        $work = $this->getReference('work.1');
        $count = count($work->getContributions());
        $this->importer->addContribution($work, 'Felicia', '[role_a]');
        $this->assertSame($count + 1, count($work->getContributions()));
        $con = $work->getContributions()[1];
        $this->assertNotNull($con);

        $this->assertSame('Felicia', $con->getPerson()->getFullName());
        $this->assertSame('Role 0', $con->getRole()->getLabel());
        $this->assertSame($work, $con->getWork());
    }

    public function testAddDateUnknownCategory() : void {
        $this->expectExceptionMessageMatches('/Cannot find date category/');
        /** @var Work $work */
        $work = $this->getReference('work.1');
        $this->importer->addDate($work, '1900', 'abc');
    }

    public function testAddDateMalformed() : void {
        $this->expectExceptionMessageMatches('/Malformed date/');
        /** @var Work $work */
        $work = $this->getReference('work.1');
        $this->importer->addDate($work, '1900-01', 'Category 0');
    }

    public function testAddDate() : void {
        /** @var Work $work */
        $work = $this->getReference('work.1');
        $this->importer->addDate($work, '1900', 'Category 0');
        $date = $work->getDates()[0];
        $this->assertNotNull($date);
        $this->assertSame(UnitOfWork::STATE_NEW, $this->state($date));
        $this->assertSame('Category 0', $date->getDateCategory()->getLabel());
        $this->assertSame('1900', $date->getStart());
    }

    public function testFindPublisherNew() : void {
        $publisher = $this->importer->findPublisher('Marge');
        $this->assertNotNull($publisher);
        $this->assertSame('Marge', $publisher->getName());
        $this->assertSame(UnitOfWork::STATE_NEW, $this->state($publisher));
    }

    public function testFindPublisherExisting() : void {
        $publisher = $this->importer->findPublisher('Name 1');
        $this->assertNotNull($publisher);
        $this->assertSame('Name 1', $publisher->getName());
        $this->assertSame(UnitOfWork::STATE_MANAGED, $this->state($publisher));
    }

    /**
     * @dataProvider yesNoData
     *
     * @param mixed $expected
     * @param mixed $value
     * @param mixed $exception
     */
    public function testYesNo($expected, $value, $exception = false) : void {
        if ($exception) {
            $this->expectExceptionMessageMatches('|Malformed Yes/No field|');
        }
        $this->assertSame($expected, $this->importer->yesNo($value));
    }

    public function yesNoData() {
        return [
            [true, 'yes'],
            [true, 'Yes'],
            [true, 'YES'],
            [true, 'Y'],
            [true, 'y'],

            [false, 'no'],
            [false, 'No'],
            [false, 'NO'],
            [false, 'N'],
            [false, 'n'],

            [null, ''],
            [null, 'abc', true],
        ];
    }

    public function testAddSubjectUnknown() : void {
        $this->expectExceptionMessageMatches('/Unknown subject/');
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->addSubjects($work, 'unknown');
    }

    public function testSubjectBlank() : void {
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->addSubjects($work, '');
        $this->assertCount(1, $work->getSubjects());
    }

    public function testAddSubject() : void {
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->addSubjects($work, 'Subject 0');
        $this->assertCount(2, $work->getSubjects());
    }

    public function testSetGenreUnknown() : void {
        $this->expectExceptionMessageMatches('/Unknown genre/');
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->setGenre($work, 'abc');
    }

    public function testSetGenre() : void {
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->setGenre($work, 'Genre 0');
        $this->assertSame('genre_0', $work->getGenre()->getName());
    }

    /**
     * @dataProvider getUrlsData
     *
     * @param mixed $expected
     * @param mixed $data
     */
    public function testGetUrls($expected, $data) : void {
        $urls = $this->importer->getUrls($data);
        $this->assertSame($expected, $urls);
    }

    public function getUrlsData() {
        return [
            [[], 'abc123'],
            [['http://example.com'], 'also here: http://example.com and there.'],
            [['https://x.com', 'https://y.com/with/path'], 'also here: https://x.com and https://y.com/with/path'],
        ];
    }

    public function testSetWorkCategoryBlank() : void {
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $category = $work->getWorkCategory();
        $this->importer->setWorkCategory($work, '');
        $this->assertSame($category, $work->getWorkCategory());
    }

    public function testSetWorkCategoryUnknown() : void {
        $this->expectExceptionMessageMatches('/Unknown work category/');
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->setWorkCategory($work, 'abc123');
    }

    public function testSetWorkCategory() : void {
        $this->expectExceptionMessageMatches('/Unknown work category/');
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->setWorkCategory($work, 'Category 2');
        $this->assertSame('category_2', $work->getWorkCategory()->getName());
    }

    protected function setUp() : void {
        parent::setUp();
        $this->importer = self::$container->get(Importer::class);
    }
}
