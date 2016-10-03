<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160928144340 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_957A647992FC23A8 ON fos_user');
        $this->addSql('ALTER TABLE fos_user DROP username, DROP username_canonical, DROP last_login, DROP locked, DROP expired, DROP expires_at, DROP confirmation_token, DROP password_requested_at, DROP credentials_expired, DROP credentials_expire_at');
        $this->addSql('RENAME TABLE fos_user TO `user`');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE `user` TO `fos_user`');
        $this->addSql('ALTER TABLE fos_user ADD username VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD username_canonical VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD last_login DATETIME DEFAULT NULL, ADD locked TINYINT(1) NOT NULL, ADD expired TINYINT(1) NOT NULL, ADD expires_at DATETIME DEFAULT NULL, ADD confirmation_token VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD password_requested_at DATETIME DEFAULT NULL, ADD credentials_expired TINYINT(1) NOT NULL, ADD credentials_expire_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical)');
    }
}
