<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415191346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE issue ADD status SMALLINT NOT NULL');

        // Syntaxe PostgreSQL pour modifier le type d'une colonne
        $this->addSql('ALTER TABLE project ALTER COLUMN key_code TYPE VARCHAR(5)');
        // Pour ajouter la contrainte NOT NULL (si elle n'existe pas déjà)
        $this->addSql('ALTER TABLE project ALTER COLUMN key_code SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // Syntaxe PostgreSQL pour annuler les modifications
        $this->addSql('ALTER TABLE project ALTER COLUMN key_code TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE issue DROP status');
    }
}