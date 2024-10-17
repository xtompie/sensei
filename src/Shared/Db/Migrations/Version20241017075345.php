<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017075345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, reset_at DATETIME DEFAULT NULL, role VARCHAR(255) NOT NULL, INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), UNIQUE INDEX idx_email (email), INDEX idx_reset_token (reset_token), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE image (ai INT AUTO_INCREMENT NOT NULL, id VARCHAR(255) NOT NULL, media VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, UNIQUE INDEX idx_id (id), INDEX idx_media (media), INDEX idx_source (source), PRIMARY KEY(ai))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE image');
    }
}
