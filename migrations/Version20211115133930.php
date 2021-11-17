<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211115133930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedido CHANGE estado_pedido_id estado_pedido_id INT DEFAULT NULL, CHANGE precio_total precio_total DOUBLE PRECISION, CHANGE fecha_realizacion fecha_realizacion DATETIME NOT NULL, CHANGE direccion_entrega direccion_entrega LONGTEXT NOT NULL, CHANGE observaciones observaciones LONGTEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedido CHANGE estado_pedido_id estado_pedido_id INT DEFAULT 1, CHANGE precio_total precio_total DOUBLE PRECISION, CHANGE fecha_realizacion fecha_realizacion DATETIME DEFAULT NOT NULL, CHANGE direccion_entrega direccion_entrega LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE observaciones observaciones LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
