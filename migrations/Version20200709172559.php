<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200709172559 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reviews ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE reviews DROP reviever_name');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES site_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6970EB0F7E3C61F9 ON reviews (owner_id)');
        $this->addSql('ALTER TABLE site_user ADD name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE site_user DROP name');
        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT FK_6970EB0F7E3C61F9');
        $this->addSql('DROP INDEX IDX_6970EB0F7E3C61F9');
        $this->addSql('ALTER TABLE reviews ADD reviever_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reviews DROP owner_id');
    }
}
