<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101002325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE podcastor (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C8BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE podcastor ADD CONSTRAINT FK_3CB68615BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA79C79542');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA79C79542 FOREIGN KEY (podcaster_id) REFERENCES podcastor (id)');
        $this->addSql('ALTER TABLE podcast DROP FOREIGN KEY FK_D7E805BD79C79542');
        $this->addSql('ALTER TABLE podcast ADD CONSTRAINT FK_D7E805BD79C79542 FOREIGN KEY (podcaster_id) REFERENCES podcastor (id)');
        $this->addSql('ALTER TABLE user ADD dtype VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA79C79542');
        $this->addSql('ALTER TABLE podcast DROP FOREIGN KEY FK_D7E805BD79C79542');
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C8BF396750');
        $this->addSql('ALTER TABLE podcastor DROP FOREIGN KEY FK_3CB68615BF396750');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE podcastor');
        $this->addSql('ALTER TABLE podcast DROP FOREIGN KEY FK_D7E805BD79C79542');
        $this->addSql('ALTER TABLE podcast ADD CONSTRAINT FK_D7E805BD79C79542 FOREIGN KEY (podcaster_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA79C79542');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA79C79542 FOREIGN KEY (podcaster_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user DROP dtype');
    }
}
