<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241201192440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_email ON backend_user');
        $this->addSql('ALTER TABLE backend_user ADD tenant VARCHAR(255) NOT NULL');
        $this->addSql('CREATE INDEX idx_tenant ON backend_user (tenant)');
        $this->addSql('CREATE INDEX idx_email ON backend_user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_tenant ON backend_user');
        $this->addSql('DROP INDEX idx_email ON backend_user');
        $this->addSql('ALTER TABLE backend_user DROP tenant');
        $this->addSql('CREATE UNIQUE INDEX idx_email ON backend_user (email)');
    }
}
