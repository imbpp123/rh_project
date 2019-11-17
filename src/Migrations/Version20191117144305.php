<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191117144305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Hotel and Review tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(<<<'HOTEL_CREATE_QUERY'
CREATE TABLE hotel (
    id INT AUTO_INCREMENT NOT NULL, 
    name VARCHAR(255) NOT NULL, 
    address VARCHAR(255) NOT NULL, 
    rooms INT NOT NULL, 
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
HOTEL_CREATE_QUERY
);
        $this->addSql(<<<'REVIEW_CREATE_QUERY'
CREATE TABLE review (
    id INT AUTO_INCREMENT NOT NULL, 
    hotel_id INT NOT NULL, 
    text VARCHAR(255) NOT NULL, 
    score INT NOT NULL, 
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
REVIEW_CREATE_QUERY
);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE review');
    }
}
