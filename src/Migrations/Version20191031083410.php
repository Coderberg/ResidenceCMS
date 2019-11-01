<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191031083410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO `feature` (`id`, `name`) VALUES (NULL, 'Air conditioning')");
        $this->addSql("INSERT INTO `feature` (`id`, `name`) VALUES (NULL, 'Balcony')");
        $this->addSql("INSERT INTO `feature` (`id`, `name`) VALUES (NULL, 'Elevator')");
        $this->addSql("INSERT INTO `feature` (`id`, `name`) VALUES (NULL, 'Fire Alarm')");
        $this->addSql("INSERT INTO `feature` (`id`, `name`) VALUES (NULL, 'First Floor Entry')");
        $this->addSql("INSERT INTO `feature` (`id`, `name`) VALUES (NULL, 'High Impact Doors')");
        $this->addSql("INSERT INTO `feature` (`id`, `name`) VALUES (NULL, 'Patio')");
        $this->addSql("INSERT INTO `feature` (`id`, `name`) VALUES (NULL, 'Secure parking')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('TRUNCATE TABLE feature');
    }
}
