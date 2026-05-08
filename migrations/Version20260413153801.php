<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260413153801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, total_price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, owner_id INT NOT NULL, INDEX IDX_BA388B77E3C61F9 (owner_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL, product_id INT DEFAULT NULL, cart_id INT NOT NULL, INDEX IDX_F0FE25274584665A (product_id), INDEX IDX_F0FE25271AD5CDBF (cart_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, total_price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, owner_id INT NOT NULL, INDEX IDX_F52993987E3C61F9 (owner_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, unit_price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, product_id INT NOT NULL, purchase_order_id INT NOT NULL, INDEX IDX_52EA1F094584665A (product_id), INDEX IDX_52EA1F09A45D7E6A (purchase_order_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, short_description LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, cgu_accepted TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B77E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993987E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09A45D7E6A FOREIGN KEY (purchase_order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B77E3C61F9');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274584665A');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993987E3C61F9');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09A45D7E6A');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
