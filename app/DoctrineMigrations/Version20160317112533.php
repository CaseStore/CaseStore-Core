<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160317112533 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE output_document (id INT AUTO_INCREMENT NOT NULL, output_id BIGINT NOT NULL, added_by_id INT NOT NULL, public_id VARCHAR(250) NOT NULL, title VARCHAR(250) NOT NULL, original_file_name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, mime VARCHAR(255) NOT NULL, added_at DATETIME NOT NULL, INDEX IDX_91D9CFBCDE097880 (output_id), INDEX IDX_91D9CFBC55B127A4 (added_by_id), UNIQUE INDEX public_id (output_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE output_document ADD CONSTRAINT FK_91D9CFBCDE097880 FOREIGN KEY (output_id) REFERENCES output (id)');
        $this->addSql('ALTER TABLE output_document ADD CONSTRAINT FK_91D9CFBC55B127A4 FOREIGN KEY (added_by_id) REFERENCES fos_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE output_document');
    }
}
