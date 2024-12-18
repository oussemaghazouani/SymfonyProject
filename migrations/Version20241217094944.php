<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217094944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competitions (id INT AUTO_INCREMENT NOT NULL, type_competition_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date_c DATE NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_A7DD463D2DFAFA86 (type_competition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE involved_events (id INT AUTO_INCREMENT NOT NULL, competition_id INT NOT NULL, is_participating TINYINT(1) NOT NULL, INDEX IDX_301A2FDF7B39D312 (competition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_competitions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competitions ADD CONSTRAINT FK_A7DD463D2DFAFA86 FOREIGN KEY (type_competition_id) REFERENCES type_competitions (id)');
        $this->addSql('ALTER TABLE involved_events ADD CONSTRAINT FK_301A2FDF7B39D312 FOREIGN KEY (competition_id) REFERENCES competitions (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competitions DROP FOREIGN KEY FK_A7DD463D2DFAFA86');
        $this->addSql('ALTER TABLE involved_events DROP FOREIGN KEY FK_301A2FDF7B39D312');
        $this->addSql('DROP TABLE competitions');
        $this->addSql('DROP TABLE involved_events');
        $this->addSql('DROP TABLE type_competitions');
    }
}
