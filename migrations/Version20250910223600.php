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
        $this->addSql("CREATE TABLE work_requests (id BINARY(16) NOT NULL, user_id BINARY(16) NOT NULL, object_address VARCHAR(255) NOT NULL, status SMALLINT NOT NULL DEFAULT 0, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_WORK_REQUESTS_USER_ID (user_id), UNIQUE INDEX UNIQ_WORK_REQUESTS_ID (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB");
        $this->addSql('ALTER TABLE work_requests ADD CONSTRAINT FK_WORK_REQUESTS_USER FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE work_requests DROP FOREIGN KEY FK_WORK_REQUESTS_USER');
        $this->addSql('DROP TABLE IF EXISTS work_requests');
    }
}
