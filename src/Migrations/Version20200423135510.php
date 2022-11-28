<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200423135510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE standard (id INT AUTO_INCREMENT NOT NULL, classification_id INT NOT NULL, operation_id INT NOT NULL, value DOUBLE PRECISION DEFAULT NULL, comment LONGTEXT DEFAULT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME DEFAULT NULL, INDEX IDX_10F7D7872A86559F (classification_id), INDEX IDX_10F7D78744AC3583 (operation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE production (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, operation_id INT NOT NULL, product_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, comment LONGTEXT DEFAULT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME DEFAULT NULL, INDEX IDX_D3EDB1E0F92F3E70 (country_id), INDEX IDX_D3EDB1E044AC3583 (operation_id), INDEX IDX_D3EDB1E04584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, classification_id INT DEFAULT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME DEFAULT NULL, barcode VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, INDEX IDX_D34A04AD2A86559F (classification_id), INDEX IDX_D34A04ADF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classification (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE standard ADD CONSTRAINT FK_10F7D7872A86559F FOREIGN KEY (classification_id) REFERENCES classification (id)');
        $this->addSql('ALTER TABLE standard ADD CONSTRAINT FK_10F7D78744AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id)');
        $this->addSql('ALTER TABLE production ADD CONSTRAINT FK_D3EDB1E0F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE production ADD CONSTRAINT FK_D3EDB1E044AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id)');
        $this->addSql('ALTER TABLE production ADD CONSTRAINT FK_D3EDB1E04584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2A86559F FOREIGN KEY (classification_id) REFERENCES classification (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE standard DROP FOREIGN KEY FK_10F7D78744AC3583');
        $this->addSql('ALTER TABLE production DROP FOREIGN KEY FK_D3EDB1E044AC3583');
        $this->addSql('ALTER TABLE production DROP FOREIGN KEY FK_D3EDB1E04584665A');
        $this->addSql('ALTER TABLE standard DROP FOREIGN KEY FK_10F7D7872A86559F');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2A86559F');
        $this->addSql('DROP TABLE standard');
        $this->addSql('DROP TABLE production');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE classification');
    }
}
