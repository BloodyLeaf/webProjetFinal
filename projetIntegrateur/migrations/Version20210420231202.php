<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420231202 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt ADD id_etat_id INT NOT NULL');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7D3C32F8F FOREIGN KEY (id_etat_id) REFERENCES etat_emprunt (id)');
        $this->addSql('CREATE INDEX IDX_364071D7D3C32F8F ON emprunt (id_etat_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7D3C32F8F');
        $this->addSql('DROP INDEX IDX_364071D7D3C32F8F ON emprunt');
        $this->addSql('ALTER TABLE emprunt DROP id_etat_id');
    }
}
