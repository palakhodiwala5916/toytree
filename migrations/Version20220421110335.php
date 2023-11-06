<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220421110335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add refresh tokens table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("        CREATE TABLE IF NOT EXISTS refresh_tokens
    (
        id SERIAL,
    refresh_token character(128) COLLATE pg_catalog.default,
    username character(255) COLLATE pg_catalog.default,
    valid date NOT NULL,
    CONSTRAINT refresh_tokens_pkey PRIMARY KEY (id),
    CONSTRAINT refresh_tokens_refresh_token_key UNIQUE (refresh_token)
)");




    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE refresh_tokens');
    }
}
