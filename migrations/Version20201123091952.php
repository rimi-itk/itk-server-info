<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123091952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audience (name VARCHAR(255) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website_audience (website_domain VARCHAR(255) NOT NULL, audience_name VARCHAR(255) NOT NULL, INDEX IDX_2CDADD95352CFA20 (website_domain), INDEX IDX_2CDADD9559D13E4E (audience_name), PRIMARY KEY(website_domain, audience_name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE website_audience ADD CONSTRAINT FK_2CDADD95352CFA20 FOREIGN KEY (website_domain) REFERENCES website (domain)');
        $this->addSql('ALTER TABLE website_audience ADD CONSTRAINT FK_2CDADD9559D13E4E FOREIGN KEY (audience_name) REFERENCES audience (name)');
        $this->addSql('ALTER TABLE server ADD raw_data LONGTEXT NOT NULL, ADD search LONGTEXT DEFAULT NULL, CHANGE data data LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE website ADD document_root VARCHAR(255) DEFAULT NULL, ADD type VARCHAR(255) DEFAULT NULL, ADD version VARCHAR(255) DEFAULT NULL, ADD comments LONGTEXT DEFAULT NULL, ADD errors LONGTEXT DEFAULT NULL, ADD updates LONGTEXT DEFAULT NULL, ADD site_root VARCHAR(255) DEFAULT NULL, ADD search LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website_audience DROP FOREIGN KEY FK_2CDADD9559D13E4E');
        $this->addSql('DROP TABLE audience');
        $this->addSql('DROP TABLE website_audience');
        $this->addSql('ALTER TABLE server DROP raw_data, DROP search, CHANGE data data LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE website DROP document_root, DROP type, DROP version, DROP comments, DROP errors, DROP updates, DROP site_root, DROP search');
    }
}
