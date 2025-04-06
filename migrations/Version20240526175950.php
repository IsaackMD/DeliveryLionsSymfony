<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240526175950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE pedido_menu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE pedido_menu DROP CONSTRAINT FK_C192FFF4854653A');
        $this->addSql('ALTER TABLE pedido_menu DROP CONSTRAINT FK_C192FFFCCD7E912');
        $this->addSql('ALTER TABLE pedido_menu DROP CONSTRAINT pedido_menu_pkey');
        $this->addSql('ALTER TABLE pedido_menu ADD id INT NOT NULL');
        $this->addSql('ALTER TABLE pedido_menu ADD cantidad INT NOT NULL');
        $this->addSql('ALTER TABLE pedido_menu ALTER pedido_id DROP NOT NULL');
        $this->addSql('ALTER TABLE pedido_menu ALTER menu_id DROP NOT NULL');
        $this->addSql('ALTER TABLE pedido_menu ADD CONSTRAINT FK_C192FFF4854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedido_menu ADD CONSTRAINT FK_C192FFFCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedido_menu ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE pedido_menu_id_seq CASCADE');
        $this->addSql('ALTER TABLE pedido_menu DROP CONSTRAINT fk_c192fff4854653a');
        $this->addSql('ALTER TABLE pedido_menu DROP CONSTRAINT fk_c192fffccd7e912');
        $this->addSql('DROP INDEX pedido_menu_pkey');
        $this->addSql('ALTER TABLE pedido_menu DROP id');
        $this->addSql('ALTER TABLE pedido_menu DROP cantidad');
        $this->addSql('ALTER TABLE pedido_menu ALTER pedido_id SET NOT NULL');
        $this->addSql('ALTER TABLE pedido_menu ALTER menu_id SET NOT NULL');
        $this->addSql('ALTER TABLE pedido_menu ADD CONSTRAINT fk_c192fff4854653a FOREIGN KEY (pedido_id) REFERENCES pedido (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedido_menu ADD CONSTRAINT fk_c192fffccd7e912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedido_menu ADD PRIMARY KEY (pedido_id, menu_id)');
    }
}
