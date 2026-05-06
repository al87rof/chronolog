<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260430195029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events CHANGE event_img event_img VARCHAR(500) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE search_tags search_tags LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE live_events CHANGE event_id event_id VARCHAR(5) NOT NULL, CHANGE app_log app_log LONGTEXT, CHANGE riders_list riders_list LONGTEXT, CHANGE title title VARCHAR(500) DEFAULT NULL, CHANGE description description LONGTEXT, CHANGE event_img event_img VARCHAR(500) NOT NULL, CHANGE result result LONGTEXT');
        $this->addSql('ALTER TABLE riders CHANGE name name VARCHAR(100) NOT NULL, CHANGE team team VARCHAR(50) NOT NULL, CHANGE events_ids events_ids VARCHAR(500) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events CHANGE event_img event_img LONGTEXT NOT NULL, CHANGE description description TEXT NOT NULL, CHANGE search_tags search_tags TEXT NOT NULL');
        $this->addSql('ALTER TABLE live_events CHANGE event_id event_id VARCHAR(5) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, CHANGE app_log app_log TEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, CHANGE riders_list riders_list TEXT NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE title title TEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, CHANGE description description TEXT NOT NULL, CHANGE event_img event_img TEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, CHANGE result result TEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`');
        $this->addSql('ALTER TABLE riders CHANGE name name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE team team VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE events_ids events_ids VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
    }
}
