<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201230100058 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('DROP INDEX uniq_8d93d649f85e0677 ON users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74 ON users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('DROP INDEX uniq_1483a5e9f85e0677 ON users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON users (username)');
        $this->addSql('DROP INDEX uniq_1483a5e9e7927c74 ON users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON users (email)');
    }
}
