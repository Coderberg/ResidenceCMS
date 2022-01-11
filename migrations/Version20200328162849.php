<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200328162849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, currency_title VARCHAR(32) NOT NULL, code VARCHAR(3) NOT NULL, symbol_left VARCHAR(12) DEFAULT NULL, symbol_right VARCHAR(12) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deal_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE district (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_31C154878BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, sort_order SMALLINT DEFAULT NULL, url VARCHAR(255) NOT NULL, nofollow TINYINT(1) DEFAULT NULL, new_tab TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metro (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_3884E4E18BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE neighborhood (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_FEF1E9EE8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, show_in_menu TINYINT(1) NOT NULL, add_contact_form TINYINT(1) NOT NULL, contact_email_address VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, photo VARCHAR(255) NOT NULL, sort_order INT DEFAULT NULL, INDEX IDX_14B78418549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, deal_type_id INT NOT NULL, category_id INT NOT NULL, city_id INT NOT NULL, neighborhood_id INT DEFAULT NULL, metro_station_id INT DEFAULT NULL, district_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, bathrooms_number SMALLINT DEFAULT NULL, bedrooms_number SMALLINT DEFAULT NULL, max_guests SMALLINT DEFAULT NULL, address VARCHAR(255) NOT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, show_map TINYINT(1) DEFAULT NULL, price INT DEFAULT NULL, price_type VARCHAR(255) DEFAULT NULL, available_now TINYINT(1) DEFAULT NULL, state VARCHAR(255) DEFAULT \'pending\' NOT NULL, published_at DATETIME NOT NULL, priority_number INT NOT NULL, INDEX IDX_8BF21CDEF675F31B (author_id), INDEX IDX_8BF21CDE2156041B (deal_type_id), INDEX IDX_8BF21CDE12469DE2 (category_id), INDEX IDX_8BF21CDE8BAC62AF (city_id), INDEX IDX_8BF21CDE803BB24B (neighborhood_id), INDEX IDX_8BF21CDEF7D58AAA (metro_station_id), INDEX IDX_8BF21CDEB08FA272 (district_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_feature (property_id INT NOT NULL, feature_id INT NOT NULL, INDEX IDX_461A3F1E549213EC (property_id), INDEX IDX_461A3F1E60E4B879 (feature_id), PRIMARY KEY(property_id, feature_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, setting_name VARCHAR(191) NOT NULL, setting_value LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_E545A0C59F9752E0 (setting_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE district ADD CONSTRAINT IDX_31C154878BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE metro ADD CONSTRAINT IDX_3884E4E18BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE neighborhood ADD CONSTRAINT IDX_FEF1E9EE8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT IDX_14B78418549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT IDX_8BF21CDEF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT IDX_8BF21CDE2156041B FOREIGN KEY (deal_type_id) REFERENCES deal_type (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT IDX_8BF21CDE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT IDX_8BF21CDE8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT IDX_8BF21CDE803BB24B FOREIGN KEY (neighborhood_id) REFERENCES neighborhood (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT IDX_8BF21CDEF7D58AAA FOREIGN KEY (metro_station_id) REFERENCES metro (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT IDX_8BF21CDEB08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE property_feature ADD CONSTRAINT IDX_461A3F1E549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_feature ADD CONSTRAINT IDX_461A3F1E60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY IDX_8BF21CDE12469DE2');
        $this->addSql('ALTER TABLE district DROP FOREIGN KEY IDX_31C154878BAC62AF');
        $this->addSql('ALTER TABLE metro DROP FOREIGN KEY IDX_3884E4E18BAC62AF');
        $this->addSql('ALTER TABLE neighborhood DROP FOREIGN KEY IDX_FEF1E9EE8BAC62AF');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY IDX_8BF21CDE8BAC62AF');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY IDX_8BF21CDE2156041B');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY IDX_8BF21CDEB08FA272');
        $this->addSql('ALTER TABLE property_feature DROP FOREIGN KEY IDX_461A3F1E60E4B879');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY IDX_8BF21CDEF7D58AAA');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY IDX_8BF21CDE803BB24B');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY IDX_14B78418549213EC');
        $this->addSql('ALTER TABLE property_feature DROP FOREIGN KEY IDX_461A3F1E549213EC');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY IDX_8BF21CDEF675F31B');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE deal_type');
        $this->addSql('DROP TABLE district');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE metro');
        $this->addSql('DROP TABLE neighborhood');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE property_feature');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE users');
    }
}
