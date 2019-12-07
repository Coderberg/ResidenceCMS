<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107180849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO `settings` (`id`, `setting_name`) VALUES (NULL, 'header_image')");
        $this->addSql('ALTER TABLE area RENAME INDEX idx_d7943d6888823a92 TO IDX_D7943D688BAC62AF');
        $this->addSql('ALTER TABLE property RENAME INDEX idx_8bf21cde44ac3583 TO IDX_8BF21CDE2156041B');
        $this->addSql('ALTER TABLE property RENAME INDEX idx_8bf21cde88823a92 TO IDX_8BF21CDE8BAC62AF');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("DELETE FROM `settings` WHERE `settings`.`setting_name`='header_image'");
        $this->addSql('ALTER TABLE area RENAME INDEX idx_d7943d688bac62af TO IDX_D7943D6888823A92');
        $this->addSql('ALTER TABLE property RENAME INDEX idx_8bf21cde2156041b TO IDX_8BF21CDE44AC3583');
        $this->addSql('ALTER TABLE property RENAME INDEX idx_8bf21cde8bac62af TO IDX_8BF21CDE88823A92');
    }
}
