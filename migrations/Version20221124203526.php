<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124203526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_stock (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, stock_id INT DEFAULT NULL, name VARCHAR(40) NOT NULL, quantity INT NOT NULL, start_price DOUBLE PRECISION NOT NULL, position VARCHAR(10) NOT NULL, INDEX IDX_A9F4AD389D86650F (user_id_id), INDEX IDX_A9F4AD38DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_stock ADD CONSTRAINT FK_A9F4AD389D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_stock ADD CONSTRAINT FK_A9F4AD38DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_660CBA619D86650F');
        $this->addSql('DROP TABLE stocks');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stocks (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, name VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantity INT NOT NULL, start_price DOUBLE PRECISION NOT NULL, position VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_56F798059D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_660CBA619D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_stock DROP FOREIGN KEY FK_A9F4AD389D86650F');
        $this->addSql('ALTER TABLE user_stock DROP FOREIGN KEY FK_A9F4AD38DCD6110');
        $this->addSql('DROP TABLE user_stock');
    }
}
