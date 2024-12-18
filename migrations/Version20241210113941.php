<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<<< HEAD:migrations/Version20241210113941.php
final class Version20241210113941 extends AbstractMigration
========
final class Version20241209122233 extends AbstractMigration
>>>>>>>> origin/produit:migrations/Version20241209122233.php
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20241210113941.php
        $this->addSql('ALTER TABLE materiels ADD acquisition_date DATE DEFAULT NULL');
========

>>>>>>>> origin/produit:migrations/Version20241209122233.php
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20241210113941.php
        $this->addSql('ALTER TABLE materiels DROP acquisition_date');
========

>>>>>>>> origin/produit:migrations/Version20241209122233.php
    }
}
