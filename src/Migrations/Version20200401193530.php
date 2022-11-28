<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200401193530 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME NOT NULL, INDEX IDX_8157AA0FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weighting (id INT AUTO_INCREMENT NOT NULL, profile_id INT NOT NULL, criterion_id INT NOT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_C3AE6B83CCFA12B8 (profile_id), INDEX IDX_C3AE6B8397766307 (criterion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE weighting ADD CONSTRAINT FK_C3AE6B83CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE weighting ADD CONSTRAINT FK_C3AE6B8397766307 FOREIGN KEY (criterion_id) REFERENCES criterion (id)');
        $this->addSql('ALTER TABLE user ADD active_profile_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ED58704E FOREIGN KEY (active_profile_id) REFERENCES profile (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649ED58704E ON user (active_profile_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649ED58704E');
        $this->addSql('ALTER TABLE weighting DROP FOREIGN KEY FK_C3AE6B83CCFA12B8');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE weighting');
        $this->addSql('DROP INDEX IDX_8D93D649ED58704E ON user');
        $this->addSql('ALTER TABLE user DROP active_profile_id');
    }
}
