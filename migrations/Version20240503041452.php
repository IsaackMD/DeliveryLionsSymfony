<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503041452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE cat_comida_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE menu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE negocio_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pedido_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cat_comida (id INT NOT NULL, negocio_id INT DEFAULT NULL, categoria VARCHAR(255) NOT NULL, estatus BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4FA9F9A27D879E4F ON cat_comida (negocio_id)');
        $this->addSql('CREATE TABLE menu (id INT NOT NULL, negocio_id INT DEFAULT NULL, nom_menu VARCHAR(255) NOT NULL, precio DOUBLE PRECISION NOT NULL, descrip VARCHAR(255) NOT NULL, imagen VARCHAR(255) NOT NULL, complemento VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7D053A937D879E4F ON menu (negocio_id)');
        $this->addSql('CREATE TABLE negocio (id INT NOT NULL, negocio VARCHAR(255) NOT NULL, estatus BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pedido (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pedido_user (pedido_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(pedido_id, user_id))');
        $this->addSql('CREATE INDEX IDX_FC8FC3254854653A ON pedido_user (pedido_id)');
        $this->addSql('CREATE INDEX IDX_FC8FC325A76ED395 ON pedido_user (user_id)');
        $this->addSql('CREATE TABLE pedido_menu (pedido_id INT NOT NULL, menu_id INT NOT NULL, PRIMARY KEY(pedido_id, menu_id))');
        $this->addSql('CREATE INDEX IDX_C192FFF4854653A ON pedido_menu (pedido_id)');
        $this->addSql('CREATE INDEX IDX_C192FFFCCD7E912 ON pedido_menu (menu_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, negocio_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D93D6497D879E4F ON "user" (negocio_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE cat_comida ADD CONSTRAINT FK_4FA9F9A27D879E4F FOREIGN KEY (negocio_id) REFERENCES negocio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A937D879E4F FOREIGN KEY (negocio_id) REFERENCES negocio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedido_user ADD CONSTRAINT FK_FC8FC3254854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedido_user ADD CONSTRAINT FK_FC8FC325A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedido_menu ADD CONSTRAINT FK_C192FFF4854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedido_menu ADD CONSTRAINT FK_C192FFFCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6497D879E4F FOREIGN KEY (negocio_id) REFERENCES negocio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cat_comida_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE menu_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE negocio_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pedido_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE cat_comida DROP CONSTRAINT FK_4FA9F9A27D879E4F');
        $this->addSql('ALTER TABLE menu DROP CONSTRAINT FK_7D053A937D879E4F');
        $this->addSql('ALTER TABLE pedido_user DROP CONSTRAINT FK_FC8FC3254854653A');
        $this->addSql('ALTER TABLE pedido_user DROP CONSTRAINT FK_FC8FC325A76ED395');
        $this->addSql('ALTER TABLE pedido_menu DROP CONSTRAINT FK_C192FFF4854653A');
        $this->addSql('ALTER TABLE pedido_menu DROP CONSTRAINT FK_C192FFFCCD7E912');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6497D879E4F');
        $this->addSql('DROP TABLE cat_comida');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE negocio');
        $this->addSql('DROP TABLE pedido');
        $this->addSql('DROP TABLE pedido_user');
        $this->addSql('DROP TABLE pedido_menu');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
