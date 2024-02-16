<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108104654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis ADD approved TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_292FFF1DA76ED395 ON vehicule (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP approved');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DA76ED395');
        $this->addSql('DROP INDEX IDX_292FFF1DA76ED395 ON vehicule');
    }
}
