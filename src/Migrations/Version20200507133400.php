<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200507133400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_scale (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, profile_id INT DEFAULT NULL, product_id INT DEFAULT NULL, creation_date DATETIME DEFAULT NULL, modification_date DATETIME DEFAULT NULL, score DOUBLE PRECISION DEFAULT NULL, transparency DOUBLE PRECISION DEFAULT NULL, status JSON DEFAULT NULL, decision JSON DEFAULT NULL, INDEX IDX_4D1A5ED8A76ED395 (user_id), INDEX IDX_4D1A5ED8CCFA12B8 (profile_id), INDEX IDX_4D1A5ED84584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_scale ADD CONSTRAINT FK_4D1A5ED8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_scale ADD CONSTRAINT FK_4D1A5ED8CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE product_scale ADD CONSTRAINT FK_4D1A5ED84584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_scale');
    }
}
