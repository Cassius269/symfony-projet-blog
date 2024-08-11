<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811190603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification ADD article_id INT DEFAULT NULL, ADD podcast_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA7294869C ON notification (article_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA786136AB ON notification (podcast_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA7294869C');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA786136AB');
        $this->addSql('DROP INDEX IDX_BF5476CA7294869C ON notification');
        $this->addSql('DROP INDEX IDX_BF5476CA786136AB ON notification');
        $this->addSql('ALTER TABLE notification DROP article_id, DROP podcast_id');
    }
}
