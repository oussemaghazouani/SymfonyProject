<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217114733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE involved_events (id INT AUTO_INCREMENT NOT NULL, competition_id INT NOT NULL, is_participating TINYINT(1) NOT NULL, INDEX IDX_301A2FDF7B39D312 (competition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_competitions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE involved_events ADD CONSTRAINT FK_301A2FDF7B39D312 FOREIGN KEY (competition_id) REFERENCES competitions (id)');
        $this->addSql('DROP TABLE user_competitions');
        $this->addSql('ALTER TABLE competitions ADD type_competition_id INT DEFAULT NULL, DROP idC, CHANGE type type VARCHAR(255) NOT NULL, CHANGE date date_c DATE NOT NULL');
        $this->addSql('ALTER TABLE competitions ADD CONSTRAINT FK_A7DD463D2DFAFA86 FOREIGN KEY (type_competition_id) REFERENCES type_competitions (id)');
        $this->addSql('CREATE INDEX IDX_A7DD463D2DFAFA86 ON competitions (type_competition_id)');
        $this->addSql('ALTER TABLE user ADD role VARCHAR(255) NOT NULL, DROP id_u, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql(' ALTER TABLE user ADD role VARCHAR(255) NOT NULL, DROP id_u, CHANGE email email VARCHAR(180) NOT NULL');

       

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competitions DROP FOREIGN KEY FK_A7DD463D2DFAFA86');
        $this->addSql('CREATE TABLE user_competitions (competitions_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_27113A3F14B3F5BE (competitions_id), INDEX IDX_27113A3FA76ED395 (user_id), PRIMARY KEY(competitions_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE involved_events DROP FOREIGN KEY FK_301A2FDF7B39D312');
        $this->addSql('DROP TABLE involved_events');
        $this->addSql('DROP TABLE type_competitions');
        $this->addSql('DROP INDEX IDX_A7DD463D2DFAFA86 ON competitions');
        $this->addSql('ALTER TABLE competitions ADD idC INT NOT NULL, DROP type_competition_id, CHANGE type type VARCHAR(50) NOT NULL, CHANGE date_c date DATE NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD id_u INT NOT NULL, DROP role, CHANGE email email VARCHAR(255) NOT NULL');
    }
}
