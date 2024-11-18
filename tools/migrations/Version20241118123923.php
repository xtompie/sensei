<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118123923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cms_article (id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cms_category (id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, category_id VARCHAR(255) NOT NULL, `index` VARCHAR(255) NOT NULL, INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cms_article');
        $this->addSql('DROP TABLE cms_category');
    }
}
