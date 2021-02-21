<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115203643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_660CBA619D86650F');
        $this->addSql('ALTER TABLE stocks ADD position VARCHAR(10) NOT NULL');
        $this->addSql('DROP INDEX idx_660cba619d86650f ON stocks');
        $this->addSql('CREATE INDEX IDX_56F798059D86650F ON stocks (user_id_id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_660CBA619D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F798059D86650F');
        $this->addSql('ALTER TABLE stocks DROP position');
        $this->addSql('DROP INDEX idx_56f798059d86650f ON stocks');
        $this->addSql('CREATE INDEX IDX_660CBA619D86650F ON stocks (user_id_id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F798059D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }
}
