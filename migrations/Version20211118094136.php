<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118094136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detalle_pedido (id INT AUTO_INCREMENT NOT NULL, producto_id_id INT NOT NULL, pedido_id_id INT NOT NULL, cantidad INT NOT NULL, precio_venta DOUBLE PRECISION NOT NULL, INDEX IDX_A834F5693F63963D (producto_id_id), INDEX IDX_A834F5693B31AF31 (pedido_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detalle_pedido ADD CONSTRAINT FK_A834F5693F63963D FOREIGN KEY (producto_id_id) REFERENCES producto (id)');
        $this->addSql('ALTER TABLE detalle_pedido ADD CONSTRAINT FK_A834F5693B31AF31 FOREIGN KEY (pedido_id_id) REFERENCES pedido (id)');
        $this->addSql('ALTER TABLE pedido CHANGE precio_total precio_total DOUBLE PRECISION NOT NULL, CHANGE fecha_realizacion fecha_realizacion DATETIME NOT NULL, CHANGE direccion_entrega direccion_entrega LONGTEXT NOT NULL, CHANGE observaciones observaciones LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE detalle_pedido');
        $this->addSql('ALTER TABLE pedido CHANGE precio_total precio_total DOUBLE PRECISION DEFAULT NULL, CHANGE fecha_realizacion fecha_realizacion DATETIME DEFAULT NULL, CHANGE direccion_entrega direccion_entrega LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE observaciones observaciones LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
