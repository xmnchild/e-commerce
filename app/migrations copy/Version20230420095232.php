<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230420095232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) NOT NULL, total_price NUMERIC(10, 0) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, photo VARCHAR(255) NOT NULL, price NUMERIC(10, 0) NOT NULL, UNIQUE INDEX UNIQ_D34A04AD5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_cart (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, cart_id INT NOT NULL, INDEX IDX_864BAA164584665A (product_id), INDEX IDX_864BAA161AD5CDBF (cart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA164584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA161AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql("INSERT INTO product (name, description, photo, price) VALUES
('AMD Ryzen 7 5800X', '8-core, 16-thread desktop processor with a base clock speed of 3.8 GHz and a boost clock speed of 4.7 GHz', 'https://example.com/ryzen-5800x.jpg', 449.99),
('Intel Core i9-11900K', '8-core, 16-thread desktop processor with a base clock speed of 3.5 GHz and a boost clock speed of 5.3 GHz', 'https://example.com/core-i9-11900k.jpg', 599.99),
('Nvidia GeForce RTX 3080', 'High-end graphics card with 10 GB of GDDR6X memory and real-time ray tracing support', 'https://example.com/rtx-3080.jpg', 999.99),
('AMD Radeon RX 6900 XT', 'High-end graphics card with 16 GB of GDDR6 memory and support for hardware-accelerated ray tracing', 'https://example.com/radeon-rx-6900xt.jpg', 1199.99),
('Corsair Vengeance LPX 32GB DDR4 RAM', 'High-performance memory kit with 2x16 GB DIMMs running at 3200 MHz', 'https://example.com/corsair-vengeance-lpx.jpg', 179.99),
('Samsung 970 EVO Plus 1TB NVMe SSD', 'High-speed solid-state drive with read speeds of up to 3500 MB/s and write speeds of up to 3300 MB/s', 'https://example.com/samsung-970-evo-plus.jpg', 199.99),
('Asus ROG Strix X570-E Gaming motherboard', 'High-end motherboard with support for AMD Ryzen processors and PCIe 4.0', 'https://example.com/asus-rog-strix-x570e-gaming.jpg', 349.99),
('MSI MPG B550 Gaming Edge WiFi motherboard', 'Mid-range motherboard with support for AMD Ryzen processors and PCIe 4.0', 'https://example.com/msi-mpg-b550-gaming-edge-wifi.jpg', 179.99),
('EVGA Supernova 850 G3 power supply', 'High-end power supply with 850 watts of power and 80+ Gold efficiency', 'https://example.com/evga-supernova-850-g3.jpg', 139.99),
('Noctua NH-D15 CPU cooler', 'High-performance air cooler with dual fans and support for most modern CPU sockets', 'https://example.com/noctua-nh-d15.jpg', 99.99);");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA164584665A');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA161AD5CDBF');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_cart');
        $this->addSql('DROP TABLE user');
    }
}
