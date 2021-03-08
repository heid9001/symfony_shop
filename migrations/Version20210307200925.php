<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210307200925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE categories_id_seq1 CASCADE');
        $this->addSql('DROP SEQUENCE products_id_seq1 CASCADE');
        $this->addSql('ALTER TABLE categories DROP CONSTRAINT FK_3AF346685550C4ED');
        $this->addSql('ALTER TABLE categories ALTER pid SET NOT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF346685550C4ED FOREIGN KEY (pid) REFERENCES categories (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products DROP CONSTRAINT FK_B3BA5A5A4B30D9C4');
        $this->addSql('ALTER TABLE products ALTER cid SET NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A4B30D9C4 FOREIGN KEY (cid) REFERENCES categories (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE categories_id_seq1 INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE products_id_seq1 INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE categories DROP CONSTRAINT fk_3af346685550c4ed');
        $this->addSql('ALTER TABLE categories ALTER pid DROP NOT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT fk_3af346685550c4ed FOREIGN KEY (pid) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products DROP CONSTRAINT fk_b3ba5a5a4b30d9c4');
        $this->addSql('ALTER TABLE products ALTER cid DROP NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT fk_b3ba5a5a4b30d9c4 FOREIGN KEY (cid) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
