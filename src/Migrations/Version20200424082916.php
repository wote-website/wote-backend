<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200424082916 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE source (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME DEFAULT NULL, INDEX IDX_5F8A7F73F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F73F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE standard ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE standard ADD CONSTRAINT FK_10F7D787953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('CREATE INDEX IDX_10F7D787953C1C61 ON standard (source_id)');
        $this->addSql('ALTER TABLE classification ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE classification ADD CONSTRAINT FK_456BD231F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_456BD231F675F31B ON classification (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE standard DROP FOREIGN KEY FK_10F7D787953C1C61');
        $this->addSql('DROP TABLE source');
        $this->addSql('ALTER TABLE classification DROP FOREIGN KEY FK_456BD231F675F31B');
        $this->addSql('DROP INDEX IDX_456BD231F675F31B ON classification');
        $this->addSql('ALTER TABLE classification DROP author_id');
        $this->addSql('DROP INDEX IDX_10F7D787953C1C61 ON standard');
        $this->addSql('ALTER TABLE standard DROP source_id');
    }
}
