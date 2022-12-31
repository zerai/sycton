<?php

declare(strict_types=1);

namespace IamServiceDbMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230225150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initialize event-store and projection tables.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE event_streams (no BIGSERIAL NOT NULL, real_stream_name VARCHAR(150) NOT NULL, stream_name CHAR(41) NOT NULL, metadata JSONB DEFAULT NULL, category VARCHAR(150) DEFAULT NULL, PRIMARY KEY(no))');
        $this->addSql('CREATE INDEX event_streams_category_idx ON event_streams (category)');
        $this->addSql('CREATE UNIQUE INDEX event_streams_stream_name_key ON event_streams (stream_name)');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE projections (no BIGSERIAL NOT NULL, name VARCHAR(150) NOT NULL, "position" JSONB DEFAULT NULL, state JSONB DEFAULT NULL, status VARCHAR(28) NOT NULL, locked_until CHAR(26) DEFAULT NULL, PRIMARY KEY(no))');
        $this->addSql('CREATE UNIQUE INDEX projections_name_key ON projections (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE event_streams');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE projections');
    }
}
