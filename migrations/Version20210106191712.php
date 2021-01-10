<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106191712 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("INSERT INTO settings (setting_name, setting_value) VALUES ('show_filter_by_features', '0')");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DELETE FROM settings WHERE setting_name='show_filter_by_features'");
    }
}
