<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230419142930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
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
    }
}
