<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027231213 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15BB3453DB');
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15D60322AC');
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15217BBB47');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15BB3453DB FOREIGN KEY (work_id) REFERENCES work (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE date_year DROP FOREIGN KEY FK_9B5CFAC32D3FA83E');
        $this->addSql('ALTER TABLE date_year DROP FOREIGN KEY FK_9B5CFAC3BB3453DB');
        $this->addSql('ALTER TABLE date_year ADD CONSTRAINT FK_9B5CFAC32D3FA83E FOREIGN KEY (date_category_id) REFERENCES date_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE date_year ADD CONSTRAINT FK_9B5CFAC3BB3453DB FOREIGN KEY (work_id) REFERENCES work (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176B4BB6BBC');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176AD45A6FD');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176B4BB6BBC FOREIGN KEY (birth_place_id) REFERENCES place (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176AD45A6FD FOREIGN KEY (death_place_id) REFERENCES place (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7AD66D884B');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7AD66D884B FOREIGN KEY (subject_source_id) REFERENCES subject_source (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E688040C86FCE');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E68804296D31F');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E6880D877D21');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E688040C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E68804296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E6880D877D21 FOREIGN KEY (work_category_id) REFERENCES work_type (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7AD66D884B');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7AD66D884B FOREIGN KEY (subject_source_id) REFERENCES subject_source (id)');
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15BB3453DB');
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15D60322AC');
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15217BBB47');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15BB3453DB FOREIGN KEY (work_id) REFERENCES work (id)');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE date_year DROP FOREIGN KEY FK_9B5CFAC32D3FA83E');
        $this->addSql('ALTER TABLE date_year DROP FOREIGN KEY FK_9B5CFAC3BB3453DB');
        $this->addSql('ALTER TABLE date_year ADD CONSTRAINT FK_9B5CFAC32D3FA83E FOREIGN KEY (date_category_id) REFERENCES date_category (id)');
        $this->addSql('ALTER TABLE date_year ADD CONSTRAINT FK_9B5CFAC3BB3453DB FOREIGN KEY (work_id) REFERENCES work (id)');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176B4BB6BBC');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176AD45A6FD');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176B4BB6BBC FOREIGN KEY (birth_place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176AD45A6FD FOREIGN KEY (death_place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E68804296D31F');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E688040C86FCE');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E6880D877D21');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E68804296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E688040C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id)');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E6880D877D21 FOREIGN KEY (work_category_id) REFERENCES work_type (id)');
    }
}
