<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191109140319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE metro (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_3884E4E18BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE metro ADD CONSTRAINT FK_3884E4E18BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE property ADD metro_station_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEF7D58AAA FOREIGN KEY (metro_station_id) REFERENCES metro (id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDEF7D58AAA ON property (metro_station_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEF7D58AAA');
        $this->addSql('DROP TABLE metro');
        $this->addSql('DROP INDEX IDX_8BF21CDEF7D58AAA ON property');
        $this->addSql('ALTER TABLE property DROP metro_station_id');
    }
}
