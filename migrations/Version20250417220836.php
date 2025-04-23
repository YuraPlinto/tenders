<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Создание таблицы Tender
 */
final class Version20250417220836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table Tender';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE tender (
                id         INT AUTO_INCREMENT NOT NULL,
                status_id  INT,
                name       VARCHAR(255) NOT NULL,
                updated_at DATETIME NOT NULL,
                code       VARCHAR(255) NOT NULL,
                `number`   VARCHAR(255) NOT NULL,
                UNIQUE INDEX UNIQ_42057A7777153098 (code),
                UNIQUE INDEX UNIQ_42057A7796901F54 (`number`),
                INDEX IDX_42057A776BF700BD (status_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tender ADD CONSTRAINT FK_42057A776BF700BD FOREIGN KEY (status_id) REFERENCES status (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE tender DROP FOREIGN KEY FK_42057A776BF700BD
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tender
        SQL);
    }
}
