<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719212937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE main_image_illustration (id INT AUTO_INCREMENT NOT NULL, photographer_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, image_filename VARCHAR(255) NOT NULL, source VARCHAR(255) DEFAULT NULL, INDEX IDX_F267D97D53EC1A21 (photographer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photographer (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, pseudonyme VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE main_image_illustration ADD CONSTRAINT FK_F267D97D53EC1A21 FOREIGN KEY (photographer_id) REFERENCES photographer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE main_image_illustration DROP FOREIGN KEY FK_F267D97D53EC1A21');
        $this->addSql('DROP TABLE main_image_illustration');
        $this->addSql('DROP TABLE photographer');
    }
}
