<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024231647 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('RENAME TABLE audio TO nines_media_audio;');
        $this->addSql('RENAME TABLE image TO nines_media_image;');
        $this->addSql('RENAME TABLE link TO nines_media_link;');
        $this->addSql('RENAME TABLE pdf TO nines_media_pdf;');

        $this->addSql('RENAME TABLE blog_page TO nines_blog_page;');
        $this->addSql('RENAME TABLE blog_post TO nines_blog_post;');
        $this->addSql('RENAME TABLE blog_post_category TO nines_blog_post_category;');
        $this->addSql('RENAME TABLE blog_post_status TO nines_blog_post_status;');

        $this->addSql('RENAME TABLE comment TO nines_feedback_comment;');
        $this->addSql('RENAME TABLE comment_note TO nines_feedback_comment_note;');
        $this->addSql('RENAME TABLE comment_status TO nines_feedback_comment_status;');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('RENAME TABLE nines_media_audio TO audio;');
        $this->addSql('RENAME TABLE nines_media_image TO image;');
        $this->addSql('RENAME TABLE nines_media_link TO link;');
        $this->addSql('RENAME TABLE nines_media_pdf TO pdf;');

        $this->addSql('RENAME TABLE nines_blog_page TO blog_page;');
        $this->addSql('RENAME TABLE nines_blog_post TO blog_post;');
        $this->addSql('RENAME TABLE nines_blog_post_category TO blog_post_category;');
        $this->addSql('RENAME TABLE nines_blog_post_status TO blog_post_status;');

        $this->addSql('RENAME TABLE nines_feedback_comment TO comment;');
        $this->addSql('RENAME TABLE nines_feedback_comment_note TO comment_note;');
        $this->addSql('RENAME TABLE nines_feedback_comment_status TO comment_status;');
    }
}
