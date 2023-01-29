<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230126183247 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE balances (id VARCHAR(100) NOT NULL, phone_id VARCHAR(100) NOT NULL, balance DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_41A7E40F3B7323CB ON balances (phone_id)');
        $this->addSql('COMMENT ON COLUMN balances.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN balances.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE transactions (id VARCHAR(100) NOT NULL, phone_id VARCHAR(100) NOT NULL, hash VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, card JSON NOT NULL, completed_on_time VARCHAR(10) NOT NULL, sum DOUBLE PRECISION DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAA81A4C3B7323CB ON transactions (phone_id)');
        $this->addSql('COMMENT ON COLUMN transactions.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE balances ADD CONSTRAINT FK_41A7E40F3B7323CB FOREIGN KEY (phone_id) REFERENCES phones (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C3B7323CB FOREIGN KEY (phone_id) REFERENCES phones (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phones ADD status VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE balances DROP CONSTRAINT FK_41A7E40F3B7323CB');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4C3B7323CB');
        $this->addSql('DROP TABLE balances');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('ALTER TABLE users ALTER password TYPE TEXT');
        $this->addSql('ALTER TABLE phones DROP status');
    }
}
