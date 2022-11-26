<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124203728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_stock DROP FOREIGN KEY FK_A9F4AD389D86650F');
        $this->addSql('DROP INDEX IDX_A9F4AD389D86650F ON user_stock');
        $this->addSql('ALTER TABLE user_stock CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_stock ADD CONSTRAINT FK_A9F4AD38A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A9F4AD38A76ED395 ON user_stock (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_stock DROP FOREIGN KEY FK_A9F4AD38A76ED395');
        $this->addSql('DROP INDEX IDX_A9F4AD38A76ED395 ON user_stock');
        $this->addSql('ALTER TABLE user_stock CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_stock ADD CONSTRAINT FK_A9F4AD389D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A9F4AD389D86650F ON user_stock (user_id_id)');
    }
}
