<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211102200709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedido CHANGE precio_total precio_total DOUBLE PRECISION, CHANGE fecha_realizacion fecha_realizacion DATETIME, CHANGE direccion_entrega direccion_entrega LONGTEXT, CHANGE observaciones observaciones LONGTEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedido CHANGE precio_total precio_total DOUBLE PRECISION DEFAULT NULL, CHANGE fecha_realizacion fecha_realizacion DATETIME DEFAULT NULL, CHANGE direccion_entrega direccion_entrega LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE observaciones observaciones LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
