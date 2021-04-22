<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210422011847 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE incident_emprunt DROP FOREIGN KEY FK_918A6B0ABF56F43');
        $this->addSql('DROP INDEX UNIQ_918A6B0ABF56F43 ON incident_emprunt');
        $this->addSql('ALTER TABLE incident_emprunt DROP id_emprunt_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE incident_emprunt ADD id_emprunt_id INT NOT NULL');
        $this->addSql('ALTER TABLE incident_emprunt ADD CONSTRAINT FK_918A6B0ABF56F43 FOREIGN KEY (id_emprunt_id) REFERENCES emprunt (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_918A6B0ABF56F43 ON incident_emprunt (id_emprunt_id)');
    }
}
