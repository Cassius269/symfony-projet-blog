<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605093945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, podcaster_id INT NOT NULL, podcast_id INT NOT NULL, title VARCHAR(255) NOT NULL, image_illustration VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_DDAA1CDA79C79542 (podcaster_id), INDEX IDX_DDAA1CDA786136AB (podcast_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode_guest (episode_id INT NOT NULL, guest_id INT NOT NULL, INDEX IDX_97BD6C61362B62A0 (episode_id), INDEX IDX_97BD6C619A4AA658 (guest_id), PRIMARY KEY(episode_id, guest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA79C79542 FOREIGN KEY (podcaster_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id)');
        $this->addSql('ALTER TABLE episode_guest ADD CONSTRAINT FK_97BD6C61362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_guest ADD CONSTRAINT FK_97BD6C619A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA79C79542');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA786136AB');
        $this->addSql('ALTER TABLE episode_guest DROP FOREIGN KEY FK_97BD6C61362B62A0');
        $this->addSql('ALTER TABLE episode_guest DROP FOREIGN KEY FK_97BD6C619A4AA658');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE episode_guest');
    }
}
