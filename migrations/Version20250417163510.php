<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use App\Entity\Status;

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
        $sqlQuery = "INSERT INTO status (id, name) VALUES ";
        $isFirstVal = true;
        foreach (Status::STATUSES as $id => $name) {
            if ($isFirstVal) {
                $isFirstVal = false;
            } else {
                $sqlQuery .= ', ';
            }
            $sqlQuery .= \sprintf("('%s', '%s')", $id, $name);
        }
        $this->addSql($sqlQuery);
    }

    public function down(Schema $schema): void
    {
        $sqlQuery = "DELETE FROM status WHERE name IN (";
        $isFirstVal = true;
        foreach (Status::STATUSES as $id => $name) {
            if ($isFirstVal) {
                $isFirstVal = false;
            } else {
                $sqlQuery .= ', ';
            }
            $sqlQuery .= \sprintf("'%s'", $name);
        }
        $sqlQuery .= ')';
        $this->addSql($sqlQuery);
    }
}
