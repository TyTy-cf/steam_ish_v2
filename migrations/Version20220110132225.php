<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110132225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
//         this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_language DROP FOREIGN KEY FK_7F9F8E9382F1BAF4');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, nationality VARCHAR(255) NOT NULL, flag VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_country (game_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_C6B1B649E48FD905 (game_id), INDEX IDX_C6B1B649F92F3E70 (country_id), PRIMARY KEY(game_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, director_name VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_9CE8D546F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_country ADD CONSTRAINT FK_C6B1B649E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_country ADD CONSTRAINT FK_C6B1B649F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publisher ADD CONSTRAINT FK_9CE8D546F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('DROP TABLE game_language');
        $this->addSql('DROP TABLE language');
        $this->addSql('ALTER TABLE account CHANGE wallet wallet DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE comment CHANGE up_votes up_votes INT NOT NULL, CHANGE down_votes down_votes INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_country DROP FOREIGN KEY FK_C6B1B649F92F3E70');
        $this->addSql('ALTER TABLE publisher DROP FOREIGN KEY FK_9CE8D546F92F3E70');
        $this->addSql('CREATE TABLE game_language (game_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_7F9F8E9382F1BAF4 (language_id), INDEX IDX_7F9F8E93E48FD905 (game_id), PRIMARY KEY(game_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, flag VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE game_language ADD CONSTRAINT FK_7F9F8E9382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_language ADD CONSTRAINT FK_7F9F8E93E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE game_country');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('ALTER TABLE account CHANGE wallet wallet DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE comment CHANGE up_votes up_votes INT DEFAULT NULL, CHANGE down_votes down_votes INT DEFAULT NULL');
    }
}
