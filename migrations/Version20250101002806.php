<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101002806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, category_id INT NOT NULL, main_image_illustration_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', nbre_of_views INT DEFAULT NULL, abstract LONGTEXT NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_23A0E66F675F31B (author_id), INDEX IDX_23A0E6612469DE2 (category_id), INDEX IDX_23A0E66B944B46C (main_image_illustration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, status_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_4C62E638A76ED395 (user_id), INDEX IDX_4C62E6386BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demand (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, status_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, cv VARCHAR(255) NOT NULL, decision TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, phone VARCHAR(255) NOT NULL, INDEX IDX_428D7973A76ED395 (user_id), INDEX IDX_428D79736BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, podcaster_id INT NOT NULL, podcast_id INT NOT NULL, title VARCHAR(255) NOT NULL, image_illustration VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, audio VARCHAR(255) NOT NULL, INDEX IDX_DDAA1CDA79C79542 (podcaster_id), INDEX IDX_DDAA1CDA786136AB (podcast_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode_guest (episode_id INT NOT NULL, guest_id INT NOT NULL, INDEX IDX_97BD6C61362B62A0 (episode_id), INDEX IDX_97BD6C619A4AA658 (guest_id), PRIMARY KEY(episode_id, guest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guest (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, expertise VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_image_illustration (id INT AUTO_INCREMENT NOT NULL, photographer_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) NOT NULL, source VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_F267D97D53EC1A21 (photographer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, article_id INT DEFAULT NULL, podcast_id INT DEFAULT NULL, demand_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, is_read TINYINT(1) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_BF5476CAF675F31B (author_id), INDEX IDX_BF5476CA7294869C (article_id), INDEX IDX_BF5476CA786136AB (podcast_id), INDEX IDX_BF5476CA5D022E59 (demand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photographer (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, pseudonyme VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE podcast (id INT AUTO_INCREMENT NOT NULL, podcaster_id INT NOT NULL, name VARCHAR(255) NOT NULL, image_illustration VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, slogan LONGTEXT NOT NULL, INDEX IDX_D7E805BD79C79542 (podcaster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE podcastor (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, is_accepted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, api_token VARCHAR(50) DEFAULT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66B944B46C FOREIGN KEY (main_image_illustration_id) REFERENCES main_image_illustration (id)');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C8BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6386BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE demand ADD CONSTRAINT FK_428D7973A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demand ADD CONSTRAINT FK_428D79736BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA79C79542 FOREIGN KEY (podcaster_id) REFERENCES podcastor (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id)');
        $this->addSql('ALTER TABLE episode_guest ADD CONSTRAINT FK_97BD6C61362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_guest ADD CONSTRAINT FK_97BD6C619A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE main_image_illustration ADD CONSTRAINT FK_F267D97D53EC1A21 FOREIGN KEY (photographer_id) REFERENCES photographer (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA5D022E59 FOREIGN KEY (demand_id) REFERENCES demand (id)');
        $this->addSql('ALTER TABLE podcast ADD CONSTRAINT FK_D7E805BD79C79542 FOREIGN KEY (podcaster_id) REFERENCES podcastor (id)');
        $this->addSql('ALTER TABLE podcastor ADD CONSTRAINT FK_3CB68615BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66B944B46C');
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C8BF396750');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638A76ED395');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6386BF700BD');
        $this->addSql('ALTER TABLE demand DROP FOREIGN KEY FK_428D7973A76ED395');
        $this->addSql('ALTER TABLE demand DROP FOREIGN KEY FK_428D79736BF700BD');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA79C79542');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA786136AB');
        $this->addSql('ALTER TABLE episode_guest DROP FOREIGN KEY FK_97BD6C61362B62A0');
        $this->addSql('ALTER TABLE episode_guest DROP FOREIGN KEY FK_97BD6C619A4AA658');
        $this->addSql('ALTER TABLE main_image_illustration DROP FOREIGN KEY FK_F267D97D53EC1A21');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAF675F31B');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA7294869C');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA786136AB');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA5D022E59');
        $this->addSql('ALTER TABLE podcast DROP FOREIGN KEY FK_D7E805BD79C79542');
        $this->addSql('ALTER TABLE podcastor DROP FOREIGN KEY FK_3CB68615BF396750');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE demand');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE episode_guest');
        $this->addSql('DROP TABLE guest');
        $this->addSql('DROP TABLE main_image_illustration');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE photographer');
        $this->addSql('DROP TABLE podcast');
        $this->addSql('DROP TABLE podcastor');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
