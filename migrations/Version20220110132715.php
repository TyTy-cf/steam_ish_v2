<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110132715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_5f9e962a9b6b5fba ON comment');
        $this->addSql('CREATE INDEX IDX_9474526C9B6B5FBA ON comment (account_id)');
        $this->addSql('DROP INDEX idx_5f9e962ae48fd905 ON comment');
        $this->addSql('CREATE INDEX IDX_9474526CE48FD905 ON comment (game_id)');
        $this->addSql('ALTER TABLE game ADD publisher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C40C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id)');
        $this->addSql('CREATE INDEX IDX_232B318C40C86FCE ON game (publisher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9B6B5FBA');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE48FD905');
        $this->addSql('DROP INDEX idx_9474526c9b6b5fba ON comment');
        $this->addSql('CREATE INDEX IDX_5F9E962A9B6B5FBA ON comment (account_id)');
        $this->addSql('DROP INDEX idx_9474526ce48fd905 ON comment');
        $this->addSql('CREATE INDEX IDX_5F9E962AE48FD905 ON comment (game_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C40C86FCE');
        $this->addSql('DROP INDEX IDX_232B318C40C86FCE ON game');
        $this->addSql('ALTER TABLE game DROP publisher_id');
    }
}
