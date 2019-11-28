<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128114437 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("UPDATE `settings` SET `setting_name`='meta_title' WHERE `settings`.`setting_name`='title'");
        $this->addSql("UPDATE `settings` SET `setting_name`='meta_description' WHERE `settings`.`setting_name`='description'");
        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES (NULL, 'title', 'Popular Listing')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("DELETE FROM `settings` WHERE `settings`.`setting_name`='title'");
        $this->addSql("UPDATE `settings` SET `setting_name`='title' WHERE `settings`.`setting_name`='meta_title'");
        $this->addSql("UPDATE `settings` SET `setting_name`='description' WHERE `settings`.`setting_name`='meta_description'");
    }
}
