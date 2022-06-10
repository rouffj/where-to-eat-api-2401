<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610090127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_D4E6F81B1E7706E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__address AS SELECT id, restaurant_id, street, zip_code, city FROM address');
        $this->addSql('DROP TABLE address');
        $this->addSql('CREATE TABLE address (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, restaurant_id INTEGER NOT NULL, street VARCHAR(255) NOT NULL COLLATE BINARY, zip_code VARCHAR(6) NOT NULL COLLATE BINARY, city VARCHAR(80) NOT NULL COLLATE BINARY, CONSTRAINT FK_D4E6F81B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO address (id, restaurant_id, street, zip_code, city) SELECT id, restaurant_id, street, zip_code, city FROM __temp__address');
        $this->addSql('DROP TABLE __temp__address');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D4E6F81B1E7706E ON address (restaurant_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, first_name, last_name, email FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL COLLATE BINARY, last_name VARCHAR(100) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, api_token VARCHAR(180) DEFAULT NULL, roles CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO user (id, first_name, last_name, email) SELECT id, first_name, last_name, email FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497BA2F5EB ON user (api_token)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_D4E6F81B1E7706E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__address AS SELECT id, restaurant_id, street, zip_code, city FROM address');
        $this->addSql('DROP TABLE address');
        $this->addSql('CREATE TABLE address (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, restaurant_id INTEGER NOT NULL, street VARCHAR(255) NOT NULL, zip_code VARCHAR(6) NOT NULL, city VARCHAR(80) NOT NULL)');
        $this->addSql('INSERT INTO address (id, restaurant_id, street, zip_code, city) SELECT id, restaurant_id, street, zip_code, city FROM __temp__address');
        $this->addSql('DROP TABLE __temp__address');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D4E6F81B1E7706E ON address (restaurant_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D6497BA2F5EB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, first_name, last_name, email FROM "user"');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO "user" (id, first_name, last_name, email) SELECT id, first_name, last_name, email FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
