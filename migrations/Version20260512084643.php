<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512084643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classes CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE riders_dictionary CHANGE rider_id rider_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE riders_dictionary ADD CONSTRAINT FK_C371D3EDFF881F6 FOREIGN KEY (rider_id) REFERENCES riders (id)');
        $this->addSql('CREATE INDEX IDX_C371D3EDFF881F6 ON riders_dictionary (rider_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classes CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`');
        $this->addSql('ALTER TABLE riders_dictionary DROP FOREIGN KEY FK_C371D3EDFF881F6');
        $this->addSql('DROP INDEX IDX_C371D3EDFF881F6 ON riders_dictionary');
        $this->addSql('ALTER TABLE riders_dictionary CHANGE rider_id rider_id INT NOT NULL');
    }
}
