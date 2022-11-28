<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527202856 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, country_id INT DEFAULT NULL, theme_id INT DEFAULT NULL, criterion_id INT DEFAULT NULL, profile_id INT DEFAULT NULL, value DOUBLE PRECISION DEFAULT NULL, coverage DOUBLE PRECISION DEFAULT NULL, creation_date DATETIME DEFAULT NULL, modification_date DATETIME DEFAULT NULL, INDEX IDX_329937514584665A (product_id), INDEX IDX_32993751F92F3E70 (country_id), INDEX IDX_3299375159027487 (theme_id), INDEX IDX_3299375197766307 (criterion_id), INDEX IDX_32993751CCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_329937514584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_3299375159027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_3299375197766307 FOREIGN KEY (criterion_id) REFERENCES criterion (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE country ADD name_fr VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE criterion ADD title VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE score');
        $this->addSql('ALTER TABLE country DROP name_fr');
        $this->addSql('ALTER TABLE criterion DROP title');
    }
}
