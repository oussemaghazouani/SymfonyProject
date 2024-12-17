<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108154753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add competitions table with a varchar column for type and a corresponding enum in PHP';
    }

    public function up(Schema $schema): void
    {
        // Create Competitions table with a varchar column for type
        $this->addSql('
            CREATE TABLE competitions (
                id INT AUTO_INCREMENT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                idC INT NOT NULL, 
                date DATE NOT NULL, 
                type VARCHAR(50) NOT NULL,  -- This will hold the enum values as strings
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        // Drop Competitions table
        $this->addSql('DROP TABLE competitions');
    }
}
