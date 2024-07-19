<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719214111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD main_image_illustration_id INT NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66B944B46C FOREIGN KEY (main_image_illustration_id) REFERENCES main_image_illustration (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66B944B46C ON article (main_image_illustration_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66B944B46C');
        $this->addSql('DROP INDEX IDX_23A0E66B944B46C ON article');
        $this->addSql('ALTER TABLE article DROP main_image_illustration_id');
    }
}
