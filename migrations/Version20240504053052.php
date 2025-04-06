<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240504053052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD is_verified BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD nombre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD apellido_p VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD apellido_m VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD sexo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD telefono VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD fecha_na TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP is_verified');
        $this->addSql('ALTER TABLE "user" DROP nombre');
        $this->addSql('ALTER TABLE "user" DROP apellido_p');
        $this->addSql('ALTER TABLE "user" DROP apellido_m');
        $this->addSql('ALTER TABLE "user" DROP username');
        $this->addSql('ALTER TABLE "user" DROP sexo');
        $this->addSql('ALTER TABLE "user" DROP telefono');
        $this->addSql('ALTER TABLE "user" DROP fecha_na');
    }
}
