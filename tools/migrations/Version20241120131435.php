<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120131435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image MODIFY ai INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON image');
        $this->addSql('ALTER TABLE image ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP ai');
        $this->addSql('CREATE INDEX idx_created_at ON image (created_at)');
        $this->addSql('CREATE INDEX idx_updated_at ON image (updated_at)');
        $this->addSql('ALTER TABLE image ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_created_at ON image');
        $this->addSql('DROP INDEX idx_updated_at ON image');
        $this->addSql('ALTER TABLE image ADD ai INT AUTO_INCREMENT NOT NULL, DROP created_at, DROP updated_at, DROP PRIMARY KEY, ADD PRIMARY KEY (ai)');
    }
}
