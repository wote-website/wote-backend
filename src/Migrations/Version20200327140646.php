<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200327140646 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE criterion (id INT AUTO_INCREMENT NOT NULL, theme_id INT DEFAULT NULL, country_id INT DEFAULT NULL, designation VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, code VARCHAR(4) DEFAULT NULL, unit VARCHAR(8) DEFAULT NULL, formula VARCHAR(255) DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, source_link VARCHAR(255) DEFAULT NULL, creation_date DATE DEFAULT NULL, modification_date DATE DEFAULT NULL, status JSON DEFAULT NULL, INDEX IDX_7C82227159027487 (theme_id), INDEX IDX_7C822271F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE criterion ADD CONSTRAINT FK_7C82227159027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE criterion ADD CONSTRAINT FK_7C822271F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE criterion');
    }
}
