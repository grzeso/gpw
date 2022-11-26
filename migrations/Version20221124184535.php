<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124184535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE short_name (id INT AUTO_INCREMENT NOT NULL, stock_id INT DEFAULT NULL, provider INT NOT NULL, their_name VARCHAR(100) NOT NULL, my_name VARCHAR(100) NOT NULL, ts DATETIME NOT NULL, INDEX IDX_3EE4B093DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE short_name ADD CONSTRAINT FK_3EE4B093DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('DROP TABLE name_dictionary');
        $this->addSql('ALTER TABLE log CHANGE description description VARCHAR(500) NOT NULL, CHANGE params params VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE name_dictionary (id INT AUTO_INCREMENT NOT NULL, provider INT NOT NULL, their_name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, my_name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ts DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE short_name DROP FOREIGN KEY FK_3EE4B093DCD6110');
        $this->addSql('DROP TABLE short_name');
        $this->addSql('DROP TABLE stock');
        $this->addSql('ALTER TABLE log CHANGE description description VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE params params VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
