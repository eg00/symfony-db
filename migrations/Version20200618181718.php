<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618181718 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'book and reviews tables';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE checkout DROP CONSTRAINT fk_af382d4e1717d737');
        $this->addSql('DROP SEQUENCE checkout_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reader_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE book_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ingredient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pizza_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE receipt_part_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reviews_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE book (id INT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, published_date DATE NOT NULL, isbn BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ingredient (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pizza (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, diameter INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE receipt_part (id INT NOT NULL, ingredient_id INT NOT NULL, pizza_id INT NOT NULL, weight INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DF744B4A933FE08C ON receipt_part (ingredient_id)');
        $this->addSql('CREATE INDEX IDX_DF744B4AD41D1D42 ON receipt_part (pizza_id)');
        $this->addSql('CREATE TABLE reviews (id INT NOT NULL, book_id INT NOT NULL, reviever_name VARCHAR(255) NOT NULL, content TEXT DEFAULT NULL, rating INT NOT NULL, published_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6970EB0F16A2B381 ON reviews (book_id)');
        $this->addSql('ALTER TABLE receipt_part ADD CONSTRAINT FK_DF744B4A933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt_part ADD CONSTRAINT FK_DF744B4AD41D1D42 FOREIGN KEY (pizza_id) REFERENCES pizza (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE checkout');
        $this->addSql('DROP TABLE reader');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT FK_6970EB0F16A2B381');
        $this->addSql('ALTER TABLE receipt_part DROP CONSTRAINT FK_DF744B4A933FE08C');
        $this->addSql('ALTER TABLE receipt_part DROP CONSTRAINT FK_DF744B4AD41D1D42');
        $this->addSql('DROP SEQUENCE book_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ingredient_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pizza_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE receipt_part_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reviews_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE checkout_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reader_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, book_id INT NOT NULL, reader_id INT NOT NULL, content TEXT NOT NULL, rating INT NOT NULL, published_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_794381c616a2b381 ON review (book_id)');
        $this->addSql('CREATE TABLE checkout (id INT NOT NULL, reader_id INT NOT NULL, book_id INT NOT NULL, checkout_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, return_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_af382d4e16a2b381 ON checkout (book_id)');
        $this->addSql('CREATE INDEX idx_af382d4e1717d737 ON checkout (reader_id)');
        $this->addSql('CREATE TABLE reader (id INT NOT NULL, full_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE checkout ADD CONSTRAINT fk_af382d4e1717d737 FOREIGN KEY (reader_id) REFERENCES reader (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE pizza');
        $this->addSql('DROP TABLE receipt_part');
        $this->addSql('DROP TABLE reviews');
    }
}
