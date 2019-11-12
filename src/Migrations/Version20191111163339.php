<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191111163339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'show_filter_by_city', '1')");
        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'show_filter_by_deal_type', '1')");
        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'show_filter_by_category', '1')");
        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'show_filter_by_bedrooms', '0')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("DELETE FROM `settings` WHERE `settings`.`setting_name` = 'show_filter_by_city'");
        $this->addSql("DELETE FROM `settings` WHERE `settings`.`setting_name` = 'show_filter_by_deal_type'");
        $this->addSql("DELETE FROM `settings` WHERE `settings`.`setting_name` = 'show_filter_by_category'");
        $this->addSql("DELETE FROM `settings` WHERE `settings`.`setting_name` = 'show_filter_by_bedrooms'");
    }
}
