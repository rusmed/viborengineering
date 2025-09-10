<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250910223600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create work_requests table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE work_requests (id BINARY(16) NOT NULL, contact_name VARCHAR(255) NOT NULL, object_address VARCHAR(255) NOT NULL, phone VARCHAR(30) NOT NULL, status SMALLINT NOT NULL DEFAULT 0, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_WORK_REQUESTS_ID (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS work_requests');
    }
}
