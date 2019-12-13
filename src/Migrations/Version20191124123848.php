<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191124123848 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE neighborhood RENAME INDEX uniq_fef1e9ee5e237e06 TO UNIQ_FEF1E9EE989D9B62');
        $this->addSql('ALTER TABLE neighborhood RENAME INDEX idx_d7943d688bac62af TO IDX_FEF1E9EE8BAC62AF');
        $this->addSql('ALTER TABLE property RENAME INDEX idx_8bf21cdebd0f409c TO IDX_8BF21CDE803BB24B');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE neighborhood RENAME INDEX idx_fef1e9ee8bac62af TO IDX_D7943D688BAC62AF');
        $this->addSql('ALTER TABLE neighborhood RENAME INDEX uniq_fef1e9ee989d9b62 TO UNIQ_FEF1E9EE5E237E06');
        $this->addSql('ALTER TABLE property RENAME INDEX idx_8bf21cde803bb24b TO IDX_8BF21CDEBD0F409C');
    }
}
