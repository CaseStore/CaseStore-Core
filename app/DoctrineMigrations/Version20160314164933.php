<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160314164933 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE case_study_has_output (id BIGINT AUTO_INCREMENT NOT NULL, case_study_id BIGINT NOT NULL, output_id BIGINT NOT NULL, added_by_id INT NOT NULL, removed_by_id INT DEFAULT NULL, added_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_CCB7895E70CD7994 (case_study_id), INDEX IDX_CCB7895EDE097880 (output_id), INDEX IDX_CCB7895E55B127A4 (added_by_id), INDEX IDX_CCB7895E2BD701DA (removed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE output (id BIGINT AUTO_INCREMENT NOT NULL, project_id BIGINT NOT NULL, public_id VARCHAR(250) NOT NULL, cached_title VARCHAR(250) DEFAULT NULL, cached_description VARCHAR(250) DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_CCDE149EB5B48B91 (public_id), INDEX IDX_CCDE149E166D1F9C (project_id), UNIQUE INDEX public_id (project_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE output_field_definition (id BIGINT AUTO_INCREMENT NOT NULL, project_id BIGINT NOT NULL, added_by_id INT NOT NULL, public_id VARCHAR(250) NOT NULL, type VARCHAR(250) NOT NULL, title VARCHAR(250) NOT NULL, sort INT NOT NULL, added_at DATETIME NOT NULL, INDEX IDX_D7C46E0C166D1F9C (project_id), INDEX IDX_D7C46E0C55B127A4 (added_by_id), UNIQUE INDEX public_id (project_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE output_field_value_string (id BIGINT AUTO_INCREMENT NOT NULL, output_id BIGINT NOT NULL, output_field_definition_id BIGINT NOT NULL, added_by_id INT NOT NULL, value VARCHAR(250) DEFAULT NULL, added_at DATETIME NOT NULL, INDEX IDX_D6722385DE097880 (output_id), INDEX IDX_D672238582C7C03 (output_field_definition_id), INDEX IDX_D672238555B127A4 (added_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE output_field_value_text (id BIGINT AUTO_INCREMENT NOT NULL, output_id BIGINT NOT NULL, output_field_definition_id BIGINT NOT NULL, added_by_id INT NOT NULL, value LONGTEXT DEFAULT NULL, added_at DATETIME NOT NULL, INDEX IDX_2D90BDCADE097880 (output_id), INDEX IDX_2D90BDCA82C7C03 (output_field_definition_id), INDEX IDX_2D90BDCA55B127A4 (added_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE case_study_has_output ADD CONSTRAINT FK_CCB7895E70CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id)');
        $this->addSql('ALTER TABLE case_study_has_output ADD CONSTRAINT FK_CCB7895EDE097880 FOREIGN KEY (output_id) REFERENCES output (id)');
        $this->addSql('ALTER TABLE case_study_has_output ADD CONSTRAINT FK_CCB7895E55B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_has_output ADD CONSTRAINT FK_CCB7895E2BD701DA FOREIGN KEY (removed_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE output ADD CONSTRAINT FK_CCDE149E166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE output_field_definition ADD CONSTRAINT FK_D7C46E0C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE output_field_definition ADD CONSTRAINT FK_D7C46E0C55B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE output_field_value_string ADD CONSTRAINT FK_D6722385DE097880 FOREIGN KEY (output_id) REFERENCES output (id)');
        $this->addSql('ALTER TABLE output_field_value_string ADD CONSTRAINT FK_D672238582C7C03 FOREIGN KEY (output_field_definition_id) REFERENCES output_field_definition (id)');
        $this->addSql('ALTER TABLE output_field_value_string ADD CONSTRAINT FK_D672238555B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE output_field_value_text ADD CONSTRAINT FK_2D90BDCADE097880 FOREIGN KEY (output_id) REFERENCES output (id)');
        $this->addSql('ALTER TABLE output_field_value_text ADD CONSTRAINT FK_2D90BDCA82C7C03 FOREIGN KEY (output_field_definition_id) REFERENCES output_field_definition (id)');
        $this->addSql('ALTER TABLE output_field_value_text ADD CONSTRAINT FK_2D90BDCA55B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE case_study_has_output DROP FOREIGN KEY FK_CCB7895EDE097880');
        $this->addSql('ALTER TABLE output_field_value_string DROP FOREIGN KEY FK_D6722385DE097880');
        $this->addSql('ALTER TABLE output_field_value_text DROP FOREIGN KEY FK_2D90BDCADE097880');
        $this->addSql('ALTER TABLE output_field_value_string DROP FOREIGN KEY FK_D672238582C7C03');
        $this->addSql('ALTER TABLE output_field_value_text DROP FOREIGN KEY FK_2D90BDCA82C7C03');
        $this->addSql('DROP TABLE case_study_has_output');
        $this->addSql('DROP TABLE output');
        $this->addSql('DROP TABLE output_field_definition');
        $this->addSql('DROP TABLE output_field_value_string');
        $this->addSql('DROP TABLE output_field_value_text');
    }
}
