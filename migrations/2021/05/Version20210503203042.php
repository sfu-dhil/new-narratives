<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503203042 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE citation (id INT AUTO_INCREMENT NOT NULL, entity VARCHAR(128) NOT NULL, citation LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', FULLTEXT INDEX IDX_FABD9C7EFABD9C7E6DE44026 (citation, description), INDEX IDX_FABD9C7EE284468 (entity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, public TINYINT(1) NOT NULL, original_name VARCHAR(64) NOT NULL, image_path VARCHAR(128) NOT NULL, thumb_path VARCHAR(128) NOT NULL, image_size INT NOT NULL, image_width INT NOT NULL, image_height INT NOT NULL, description LONGTEXT DEFAULT NULL, license LONGTEXT DEFAULT NULL, entity VARCHAR(80) NOT NULL, mime_type VARCHAR(64) NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', FULLTEXT INDEX IDX_C53D045F545615306DE44026 (original_name, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE link (id INT AUTO_INCREMENT NOT NULL, entity VARCHAR(128) NOT NULL, url VARCHAR(128) NOT NULL, text VARCHAR(128) DEFAULT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', FULLTEXT INDEX IDX_36AC99F1F47645AE3B8BA7C7 (url, text), INDEX IDX_36AC99F1E284468 (entity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_place (person_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_D82B4C09217BBB47 (person_id), INDEX IDX_D82B4C09DA6A219 (place_id), PRIMARY KEY(person_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) DEFAULT NULL, state VARCHAR(200) DEFAULT NULL, country VARCHAR(200) DEFAULT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', FULLTEXT INDEX place_names_ft (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_place ADD CONSTRAINT FK_D82B4C09217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_place ADD CONSTRAINT FK_D82B4C09DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE element');
        $this->addSql('ALTER TABLE person ADD birth_place_id INT DEFAULT NULL, ADD death_place_id INT DEFAULT NULL, ADD birth_date VARCHAR(12) DEFAULT NULL, ADD death_date VARCHAR(12) DEFAULT NULL, ADD biography LONGTEXT DEFAULT NULL, CHANGE created created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176B4BB6BBC FOREIGN KEY (birth_place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176AD45A6FD FOREIGN KEY (death_place_id) REFERENCES place (id)');
        $this->addSql('CREATE INDEX IDX_34DCD176B4BB6BBC ON person (birth_place_id)');
        $this->addSql('CREATE INDEX IDX_34DCD176AD45A6FD ON person (death_place_id)');
    }

    public function down(Schema $schema) : void {
        $this->throwIrreversibleMigrationException();
    }
}
