<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117153215 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phones ALTER number TYPE CHAR(20)');
        $this->addSql('ALTER TABLE phones ALTER number TYPE CHAR(20)');
        $this->addSql('ALTER TABLE users ALTER password TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phones ALTER number TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE phones ALTER number TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE users ALTER password TYPE TEXT');
    }
}
