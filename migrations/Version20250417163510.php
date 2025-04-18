<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Заполнение таблицы Status
 */
final class Version20250417163510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert values into the Status table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            INSERT INTO status (name) VALUES
                ('Открыто'),
                ('Закрыто'),
                ('Отменено')
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DELETE FROM status WHERE name IN ('Открыто', 'Закрыто', 'Отменено')
        SQL);
    }
}
