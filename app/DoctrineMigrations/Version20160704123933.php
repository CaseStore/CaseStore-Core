<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160704123933 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE case_study_field_value_text_cache (case_study_id BIGINT NOT NULL, case_study_field_definition_id BIGINT NOT NULL, value LONGTEXT DEFAULT NULL, INDEX IDX_AD02C25C70CD7994 (case_study_id), INDEX IDX_AD02C25C49917F3F (case_study_field_definition_id), PRIMARY KEY(case_study_id, case_study_field_definition_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE case_study_field_value_text_cache ADD CONSTRAINT FK_AD02C25C70CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id)');
        $this->addSql('ALTER TABLE case_study_field_value_text_cache ADD CONSTRAINT FK_AD02C25C49917F3F FOREIGN KEY (case_study_field_definition_id) REFERENCES case_study_field_definition (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE case_study_field_value_text_cache');
    }
}
