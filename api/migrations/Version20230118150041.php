<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118150041 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action_logs (id VARCHAR(100) NOT NULL, executor_id VARCHAR(100) NOT NULL, entity VARCHAR(255) NOT NULL, action VARCHAR(50) NOT NULL, payload JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_866E7D528ABD09BB ON action_logs (executor_id)');
        $this->addSql('COMMENT ON COLUMN action_logs.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE action_logs ADD CONSTRAINT FK_866E7D528ABD09BB FOREIGN KEY (executor_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action_logs DROP CONSTRAINT FK_866E7D528ABD09BB');
        $this->addSql('DROP TABLE action_logs');
    }
}
