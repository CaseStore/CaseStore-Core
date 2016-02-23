<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160223153922 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE case_study (id BIGINT AUTO_INCREMENT NOT NULL, project_id BIGINT NOT NULL, public_id VARCHAR(250) NOT NULL, cached_title VARCHAR(250) DEFAULT NULL, cached_description VARCHAR(250) DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B4966DA5B5B48B91 (public_id), INDEX IDX_B4966DA5166D1F9C (project_id), UNIQUE INDEX public_id (project_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE case_study_comment (id BIGINT AUTO_INCREMENT NOT NULL, case_study_id BIGINT NOT NULL, added_by_id INT NOT NULL, public_id VARCHAR(250) NOT NULL, body_text LONGTEXT DEFAULT NULL, added_at DATETIME NOT NULL, INDEX IDX_C6882C5670CD7994 (case_study_id), INDEX IDX_C6882C5655B127A4 (added_by_id), UNIQUE INDEX public_id (case_study_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE case_study_document (id INT AUTO_INCREMENT NOT NULL, case_study_id BIGINT NOT NULL, added_by_id INT NOT NULL, public_id VARCHAR(250) NOT NULL, title VARCHAR(250) NOT NULL, original_file_name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, mime VARCHAR(255) NOT NULL, added_at DATETIME NOT NULL, INDEX IDX_1E37AFBA70CD7994 (case_study_id), INDEX IDX_1E37AFBA55B127A4 (added_by_id), UNIQUE INDEX public_id (case_study_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE case_study_field_definition (id BIGINT AUTO_INCREMENT NOT NULL, project_id BIGINT NOT NULL, added_by_id INT NOT NULL, public_id VARCHAR(250) NOT NULL, type VARCHAR(250) NOT NULL, title VARCHAR(250) NOT NULL, sort INT NOT NULL, added_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_85E576DFB5B48B91 (public_id), INDEX IDX_85E576DF166D1F9C (project_id), INDEX IDX_85E576DF55B127A4 (added_by_id), UNIQUE INDEX public_id (project_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE case_study_field_definition_option (id BIGINT AUTO_INCREMENT NOT NULL, case_study_field_definition_id BIGINT NOT NULL, added_by_id INT NOT NULL, public_id VARCHAR(250) NOT NULL, title VARCHAR(250) NOT NULL, sort INT NOT NULL, added_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_19F674E5B5B48B91 (public_id), INDEX IDX_19F674E549917F3F (case_study_field_definition_id), INDEX IDX_19F674E555B127A4 (added_by_id), UNIQUE INDEX public_id (case_study_field_definition_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE case_study_field_value_select (id BIGINT AUTO_INCREMENT NOT NULL, case_study_id BIGINT NOT NULL, case_study_field_definition_id BIGINT NOT NULL, case_study_field_definition_option_id BIGINT DEFAULT NULL, added_by_id INT NOT NULL, removed_by_id INT DEFAULT NULL, added_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_BA47274770CD7994 (case_study_id), INDEX IDX_BA47274749917F3F (case_study_field_definition_id), INDEX IDX_BA472747D6E53CB9 (case_study_field_definition_option_id), INDEX IDX_BA47274755B127A4 (added_by_id), INDEX IDX_BA4727472BD701DA (removed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE case_study_field_value_string (id BIGINT AUTO_INCREMENT NOT NULL, case_study_id BIGINT NOT NULL, case_study_field_definition_id BIGINT NOT NULL, added_by_id INT NOT NULL, value VARCHAR(250) DEFAULT NULL, added_at DATETIME NOT NULL, INDEX IDX_6F0B7F2E70CD7994 (case_study_id), INDEX IDX_6F0B7F2E49917F3F (case_study_field_definition_id), INDEX IDX_6F0B7F2E55B127A4 (added_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE case_study_field_value_text (id BIGINT AUTO_INCREMENT NOT NULL, case_study_id BIGINT NOT NULL, case_study_field_definition_id BIGINT NOT NULL, added_by_id INT NOT NULL, value LONGTEXT DEFAULT NULL, added_at DATETIME NOT NULL, INDEX IDX_7FB1A51970CD7994 (case_study_id), INDEX IDX_7FB1A51949917F3F (case_study_field_definition_id), INDEX IDX_7FB1A51955B127A4 (added_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE case_study_has_user (id BIGINT AUTO_INCREMENT NOT NULL, case_study_id BIGINT NOT NULL, user_id INT NOT NULL, added_by_id INT NOT NULL, removed_by_id INT DEFAULT NULL, added_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_169128DA70CD7994 (case_study_id), INDEX IDX_169128DAA76ED395 (user_id), INDEX IDX_169128DA55B127A4 (added_by_id), INDEX IDX_169128DA2BD701DA (removed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE case_study_location (id BIGINT AUTO_INCREMENT NOT NULL, case_study_id BIGINT NOT NULL, added_by_id INT NOT NULL, removed_by_id INT DEFAULT NULL, public_id VARCHAR(250) NOT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, added_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_98C0AC0770CD7994 (case_study_id), INDEX IDX_98C0AC0755B127A4 (added_by_id), INDEX IDX_98C0AC072BD701DA (removed_by_id), UNIQUE INDEX public_id (case_study_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id BIGINT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, public_id VARCHAR(250) NOT NULL, title VARCHAR(250) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_2FB3D0EEB5B48B91 (public_id), INDEX IDX_2FB3D0EE7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE case_study ADD CONSTRAINT FK_B4966DA5166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE case_study_comment ADD CONSTRAINT FK_C6882C5670CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id)');
        $this->addSql('ALTER TABLE case_study_comment ADD CONSTRAINT FK_C6882C5655B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_document ADD CONSTRAINT FK_1E37AFBA70CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id)');
        $this->addSql('ALTER TABLE case_study_document ADD CONSTRAINT FK_1E37AFBA55B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_field_definition ADD CONSTRAINT FK_85E576DF166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE case_study_field_definition ADD CONSTRAINT FK_85E576DF55B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_field_definition_option ADD CONSTRAINT FK_19F674E549917F3F FOREIGN KEY (case_study_field_definition_id) REFERENCES case_study_field_definition (id)');
        $this->addSql('ALTER TABLE case_study_field_definition_option ADD CONSTRAINT FK_19F674E555B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_field_value_select ADD CONSTRAINT FK_BA47274770CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id)');
        $this->addSql('ALTER TABLE case_study_field_value_select ADD CONSTRAINT FK_BA47274749917F3F FOREIGN KEY (case_study_field_definition_id) REFERENCES case_study_field_definition (id)');
        $this->addSql('ALTER TABLE case_study_field_value_select ADD CONSTRAINT FK_BA472747D6E53CB9 FOREIGN KEY (case_study_field_definition_option_id) REFERENCES case_study_field_definition_option (id)');
        $this->addSql('ALTER TABLE case_study_field_value_select ADD CONSTRAINT FK_BA47274755B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_field_value_select ADD CONSTRAINT FK_BA4727472BD701DA FOREIGN KEY (removed_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_field_value_string ADD CONSTRAINT FK_6F0B7F2E70CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id)');
        $this->addSql('ALTER TABLE case_study_field_value_string ADD CONSTRAINT FK_6F0B7F2E49917F3F FOREIGN KEY (case_study_field_definition_id) REFERENCES case_study_field_definition (id)');
        $this->addSql('ALTER TABLE case_study_field_value_string ADD CONSTRAINT FK_6F0B7F2E55B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_field_value_text ADD CONSTRAINT FK_7FB1A51970CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id)');
        $this->addSql('ALTER TABLE case_study_field_value_text ADD CONSTRAINT FK_7FB1A51949917F3F FOREIGN KEY (case_study_field_definition_id) REFERENCES case_study_field_definition (id)');
        $this->addSql('ALTER TABLE case_study_field_value_text ADD CONSTRAINT FK_7FB1A51955B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_has_user ADD CONSTRAINT FK_169128DA70CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id)');
        $this->addSql('ALTER TABLE case_study_has_user ADD CONSTRAINT FK_169128DAA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_has_user ADD CONSTRAINT FK_169128DA55B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_has_user ADD CONSTRAINT FK_169128DA2BD701DA FOREIGN KEY (removed_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_location ADD CONSTRAINT FK_98C0AC0770CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id)');
        $this->addSql('ALTER TABLE case_study_location ADD CONSTRAINT FK_98C0AC0755B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE case_study_location ADD CONSTRAINT FK_98C0AC072BD701DA FOREIGN KEY (removed_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE case_study_comment DROP FOREIGN KEY FK_C6882C5670CD7994');
        $this->addSql('ALTER TABLE case_study_document DROP FOREIGN KEY FK_1E37AFBA70CD7994');
        $this->addSql('ALTER TABLE case_study_field_value_select DROP FOREIGN KEY FK_BA47274770CD7994');
        $this->addSql('ALTER TABLE case_study_field_value_string DROP FOREIGN KEY FK_6F0B7F2E70CD7994');
        $this->addSql('ALTER TABLE case_study_field_value_text DROP FOREIGN KEY FK_7FB1A51970CD7994');
        $this->addSql('ALTER TABLE case_study_has_user DROP FOREIGN KEY FK_169128DA70CD7994');
        $this->addSql('ALTER TABLE case_study_location DROP FOREIGN KEY FK_98C0AC0770CD7994');
        $this->addSql('ALTER TABLE case_study_field_definition_option DROP FOREIGN KEY FK_19F674E549917F3F');
        $this->addSql('ALTER TABLE case_study_field_value_select DROP FOREIGN KEY FK_BA47274749917F3F');
        $this->addSql('ALTER TABLE case_study_field_value_string DROP FOREIGN KEY FK_6F0B7F2E49917F3F');
        $this->addSql('ALTER TABLE case_study_field_value_text DROP FOREIGN KEY FK_7FB1A51949917F3F');
        $this->addSql('ALTER TABLE case_study_field_value_select DROP FOREIGN KEY FK_BA472747D6E53CB9');
        $this->addSql('ALTER TABLE case_study DROP FOREIGN KEY FK_B4966DA5166D1F9C');
        $this->addSql('ALTER TABLE case_study_field_definition DROP FOREIGN KEY FK_85E576DF166D1F9C');
        $this->addSql('ALTER TABLE case_study_comment DROP FOREIGN KEY FK_C6882C5655B127A4');
        $this->addSql('ALTER TABLE case_study_document DROP FOREIGN KEY FK_1E37AFBA55B127A4');
        $this->addSql('ALTER TABLE case_study_field_definition DROP FOREIGN KEY FK_85E576DF55B127A4');
        $this->addSql('ALTER TABLE case_study_field_definition_option DROP FOREIGN KEY FK_19F674E555B127A4');
        $this->addSql('ALTER TABLE case_study_field_value_select DROP FOREIGN KEY FK_BA47274755B127A4');
        $this->addSql('ALTER TABLE case_study_field_value_select DROP FOREIGN KEY FK_BA4727472BD701DA');
        $this->addSql('ALTER TABLE case_study_field_value_string DROP FOREIGN KEY FK_6F0B7F2E55B127A4');
        $this->addSql('ALTER TABLE case_study_field_value_text DROP FOREIGN KEY FK_7FB1A51955B127A4');
        $this->addSql('ALTER TABLE case_study_has_user DROP FOREIGN KEY FK_169128DAA76ED395');
        $this->addSql('ALTER TABLE case_study_has_user DROP FOREIGN KEY FK_169128DA55B127A4');
        $this->addSql('ALTER TABLE case_study_has_user DROP FOREIGN KEY FK_169128DA2BD701DA');
        $this->addSql('ALTER TABLE case_study_location DROP FOREIGN KEY FK_98C0AC0755B127A4');
        $this->addSql('ALTER TABLE case_study_location DROP FOREIGN KEY FK_98C0AC072BD701DA');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE7E3C61F9');
        $this->addSql('DROP TABLE case_study');
        $this->addSql('DROP TABLE case_study_comment');
        $this->addSql('DROP TABLE case_study_document');
        $this->addSql('DROP TABLE case_study_field_definition');
        $this->addSql('DROP TABLE case_study_field_definition_option');
        $this->addSql('DROP TABLE case_study_field_value_select');
        $this->addSql('DROP TABLE case_study_field_value_string');
        $this->addSql('DROP TABLE case_study_field_value_text');
        $this->addSql('DROP TABLE case_study_has_user');
        $this->addSql('DROP TABLE case_study_location');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE fos_user');
    }
}
