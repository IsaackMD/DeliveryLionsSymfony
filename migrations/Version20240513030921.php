<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513030921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu ADD cat_comida_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93D6C90DC9 FOREIGN KEY (cat_comida_id) REFERENCES cat_comida (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7D053A93D6C90DC9 ON menu (cat_comida_id)');
        $this->addSql('ALTER TABLE negocio ADD imagen VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE negocio ADD descripcion VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE negocio DROP imagen');
        $this->addSql('ALTER TABLE negocio DROP descripcion');
        $this->addSql('ALTER TABLE menu DROP CONSTRAINT FK_7D053A93D6C90DC9');
        $this->addSql('DROP INDEX IDX_7D053A93D6C90DC9');
        $this->addSql('ALTER TABLE menu DROP cat_comida_id');
    }
}
