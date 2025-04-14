<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409144137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO user (email, roles, username, password, first_name, last_name, is_verified, created_at)
                VALUES (?, '[\"ROLE_ADMIN\"]', ?, ?, ?, ?, 1, CURRENT_TIMESTAMP)",
            ['igorvukovic86@hotmail.com', 'Igor', '$2y$13$ciHV6HAbD1ulTZ6twSsQ2Ox45vHpc72jcOkMxGKSXPf9.LxDpLj9q', 'Igor', 'Вуковић']);

    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM user WHERE email = ?", ['igorvukovic86@hotmail.com']);
    }
}
