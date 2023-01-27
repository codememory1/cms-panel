<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230126150340 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bank_expressions (id VARCHAR(100) NOT NULL, bank_id VARCHAR(100) NOT NULL, transfer JSON NOT NULL, enrollment JSON NOT NULL, payment JSON NOT NULL, purchase JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4E5A79B411C8FB41 ON bank_expressions (bank_id)');
        $this->addSql('COMMENT ON COLUMN bank_expressions.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN bank_expressions.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE banks (id VARCHAR(100) NOT NULL, title VARCHAR(50) NOT NULL, number INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AB06379696901F54 ON banks (number)');
        $this->addSql('COMMENT ON COLUMN banks.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN banks.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE bank_expressions ADD CONSTRAINT FK_4E5A79B411C8FB41 FOREIGN KEY (bank_id) REFERENCES banks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_expressions DROP CONSTRAINT FK_4E5A79B411C8FB41');
        $this->addSql('DROP TABLE bank_expressions');
        $this->addSql('DROP TABLE banks');
    }
}
