<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191029092340 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, setting_name VARCHAR(191) NOT NULL, setting_value LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'name', 'Site name')");
        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'title', 'Site Title')");
        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'description', 'Site Description')");
        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'custom_code', '')");
        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'items_per_page', '6')");
        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'ymaps_key', '')");
        $this->addSql('INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, \'map_center\', \'27.188534, -81.128735\')');
        $this->addSql('INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, \'map_zoom\', \'7\')');
        $this->addSql('INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, \'currency_id\', \'1\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE settings');
    }
}
