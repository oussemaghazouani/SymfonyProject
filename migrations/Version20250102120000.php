<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to update User table for enhanced authentication system
 */
final class Version20250102120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update User table with enhanced authentication fields';
    }

    public function up(Schema $schema): void
    {
        // Add new columns to user table
        $this->addSql('ALTER TABLE user ADD identifiant VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD prenom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD user_type VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE user ADD nom_entreprise VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD domaine_activite VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD accept_terms TINYINT(1) NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE user ADD accept_privacy TINYINT(1) NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE user ADD email_verified TINYINT(1) NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE user ADD verification_code VARCHAR(6) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD verification_code_expiry DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD telephone VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD phone_verified TINYINT(1) NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE user ADD phone_verification_code VARCHAR(6) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD phone_verification_code_expiry DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD password_reset_code VARCHAR(6) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD password_reset_code_expiry DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD last_login_at DATETIME DEFAULT NULL');

        // Update existing column lengths
        $this->addSql('ALTER TABLE user MODIFY nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user MODIFY role VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Remove new columns
        $this->addSql('ALTER TABLE user DROP identifiant');
        $this->addSql('ALTER TABLE user DROP prenom');
        $this->addSql('ALTER TABLE user DROP user_type');
        $this->addSql('ALTER TABLE user DROP nom_entreprise');
        $this->addSql('ALTER TABLE user DROP domaine_activite');
        $this->addSql('ALTER TABLE user DROP accept_terms');
        $this->addSql('ALTER TABLE user DROP accept_privacy');
        $this->addSql('ALTER TABLE user DROP email_verified');
        $this->addSql('ALTER TABLE user DROP verification_code');
        $this->addSql('ALTER TABLE user DROP verification_code_expiry');
        $this->addSql('ALTER TABLE user DROP telephone');
        $this->addSql('ALTER TABLE user DROP phone_verified');
        $this->addSql('ALTER TABLE user DROP phone_verification_code');
        $this->addSql('ALTER TABLE user DROP phone_verification_code_expiry');
        $this->addSql('ALTER TABLE user DROP password_reset_code');
        $this->addSql('ALTER TABLE user DROP password_reset_code_expiry');
        $this->addSql('ALTER TABLE user DROP created_at');
        $this->addSql('ALTER TABLE user DROP last_login_at');
    }
}