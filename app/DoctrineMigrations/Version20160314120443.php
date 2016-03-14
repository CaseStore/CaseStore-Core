<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160314120443 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE case_study_field_value_integer (id BIGINT AUTO_INCREMENT NOT NULL, case_study_id BIGINT NOT NULL, case_study_field_definition_id BIGINT NOT NULL, added_by_id INT NOT NULL, value INT DEFAULT NULL, added_at DATETIME NOT NULL, INDEX IDX_2922084570CD7994 (case_study_id), INDEX IDX_2922084549917F3F (case_study_field_definition_id), INDEX IDX_2922084555B127A4 (added_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE case_study_field_value_integer ADD CONSTRAINT FK_2922084570CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id)');
        $this->addSql('ALTER TABLE case_study_field_value_integer ADD CONSTRAINT FK_2922084549917F3F FOREIGN KEY (case_study_field_definition_id) REFERENCES case_study_field_definition (id)');
        $this->addSql('ALTER TABLE case_study_field_value_integer ADD CONSTRAINT FK_2922084555B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE case_study_field_value_integer');
    }
}
