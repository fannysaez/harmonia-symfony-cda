<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260914140200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listening_history ADD track_id INT NOT NULL');
        $this->addSql('ALTER TABLE listening_history ADD CONSTRAINT FK_171029275ED23C43 FOREIGN KEY (track_id) REFERENCES track (id)');
        $this->addSql('CREATE INDEX IDX_171029275ED23C43 ON listening_history (track_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listening_history DROP FOREIGN KEY FK_171029275ED23C43');
        $this->addSql('DROP INDEX IDX_171029275ED23C43 ON listening_history');
        $this->addSql('ALTER TABLE listening_history DROP track_id');
    }
}
