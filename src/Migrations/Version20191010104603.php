<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191010104603 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, currency_title VARCHAR(32) NOT NULL, code VARCHAR(3) NOT NULL, symbol_left VARCHAR(12) DEFAULT NULL, symbol_right VARCHAR(12) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE setting CHANGE map_zoom map_zoom INT DEFAULT NULL');
        $this->addSql("INSERT INTO `currency` (`id`, `currency_title`, `code`, `symbol_left`, `symbol_right`) VALUES (NULL, 'US Dollar', 'USD', '$', NULL)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE currency');
        $this->addSql('ALTER TABLE setting CHANGE map_zoom map_zoom INT DEFAULT 7');
    }
}
