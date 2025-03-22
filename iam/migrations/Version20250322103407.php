<?php

declare(strict_types=1);

namespace IamServiceDbMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250322103407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE event_streams_no_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE projections_no_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE projections (no BIGSERIAL NOT NULL, name VARCHAR(150) NOT NULL, "position" JSONB DEFAULT NULL, state JSONB DEFAULT NULL, status VARCHAR(28) NOT NULL, locked_until CHAR(26) DEFAULT NULL, PRIMARY KEY(no))');
        $this->addSql('CREATE UNIQUE INDEX projections_name_key ON projections (name)');
        $this->addSql('CREATE TABLE event_streams (no BIGSERIAL NOT NULL, real_stream_name VARCHAR(150) NOT NULL, stream_name CHAR(41) NOT NULL, metadata JSONB DEFAULT NULL, category VARCHAR(150) DEFAULT NULL, PRIMARY KEY(no))');
        $this->addSql('CREATE UNIQUE INDEX event_streams_stream_name_key ON event_streams (stream_name)');
        $this->addSql('CREATE INDEX event_streams_category_idx ON event_streams (category)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE event_streams_no_seq CASCADE');
        $this->addSql('DROP SEQUENCE projections_no_seq CASCADE');
        $this->addSql('DROP TABLE projections');
        $this->addSql('DROP TABLE event_streams');
    }
}
