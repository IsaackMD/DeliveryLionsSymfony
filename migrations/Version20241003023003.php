<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241003023003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedido_user DROP CONSTRAINT fk_fc8fc3254854653a');
        $this->addSql('ALTER TABLE pedido_user DROP CONSTRAINT fk_fc8fc325a76ed395');
        $this->addSql('DROP TABLE pedido_user');
        $this->addSql('ALTER TABLE pedido ADD usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pedido ADD CONSTRAINT FK_C4EC16CEDB38439E FOREIGN KEY (usuario_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C4EC16CEDB38439E ON pedido (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE pedido_user (pedido_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(pedido_id, user_id))');
        $this->addSql('CREATE INDEX idx_fc8fc325a76ed395 ON pedido_user (user_id)');
        $this->addSql('CREATE INDEX idx_fc8fc3254854653a ON pedido_user (pedido_id)');
        $this->addSql('ALTER TABLE pedido_user ADD CONSTRAINT fk_fc8fc3254854653a FOREIGN KEY (pedido_id) REFERENCES pedido (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedido_user ADD CONSTRAINT fk_fc8fc325a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedido DROP CONSTRAINT FK_C4EC16CEDB38439E');
        $this->addSql('DROP INDEX IDX_C4EC16CEDB38439E');
        $this->addSql('ALTER TABLE pedido DROP usuario_id');
    }
}
