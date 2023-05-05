<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230420110003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_order (id INT AUTO_INCREMENT NOT NULL, anorder_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_5475E8C4A1B3EF5F (anorder_id), INDEX IDX_5475E8C44584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C4A1B3EF5F FOREIGN KEY (anorder_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C44584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` DROP INDEX IDX_F5299398A76ED395, ADD UNIQUE INDEX UNIQ_F5299398A76ED395 (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_order DROP FOREIGN KEY FK_5475E8C4A1B3EF5F');
        $this->addSql('ALTER TABLE product_order DROP FOREIGN KEY FK_5475E8C44584665A');
        $this->addSql('DROP TABLE product_order');
        $this->addSql('ALTER TABLE `order` DROP INDEX UNIQ_F5299398A76ED395, ADD INDEX IDX_F5299398A76ED395 (user_id)');
    }
}
