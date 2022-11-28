<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200409093524 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE priority (id INT AUTO_INCREMENT NOT NULL, profile_id INT NOT NULL, theme_id INT NOT NULL, value DOUBLE PRECISION DEFAULT NULL, priorized_weightings_sum DOUBLE PRECISION DEFAULT NULL, INDEX IDX_62A6DC27CCFA12B8 (profile_id), INDEX IDX_62A6DC2759027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE priority ADD CONSTRAINT FK_62A6DC27CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE priority ADD CONSTRAINT FK_62A6DC2759027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE criterion ADD proposal VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE weighting ADD positive_flag TINYINT(1) DEFAULT NULL, ADD negative_flag TINYINT(1) DEFAULT NULL, ADD priorized_value DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE priority');
        $this->addSql('ALTER TABLE criterion DROP proposal');
        $this->addSql('ALTER TABLE weighting DROP positive_flag, DROP negative_flag, DROP priorized_value');
    }
}
