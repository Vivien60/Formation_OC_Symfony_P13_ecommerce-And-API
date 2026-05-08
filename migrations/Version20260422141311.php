<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260422141311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP INDEX IDX_BA388B77E3C61F9, ADD UNIQUE INDEX UNIQ_BA388B77E3C61F9 (owner_id)');
        $this->addSql('ALTER TABLE product CHANGE description full_description LONGTEXT NOT NULL, CHANGE image picture VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP INDEX UNIQ_BA388B77E3C61F9, ADD INDEX IDX_BA388B77E3C61F9 (owner_id)');
        $this->addSql('ALTER TABLE product CHANGE full_description description LONGTEXT NOT NULL, CHANGE picture image VARCHAR(255) DEFAULT NULL');
    }
}
