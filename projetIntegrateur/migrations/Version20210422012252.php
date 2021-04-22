<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210422012252 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE incident_emprunt (id INT AUTO_INCREMENT NOT NULL, id_emprunt_id INT NOT NULL, description LONGTEXT NOT NULL, qte INT NOT NULL, INDEX IDX_918A6B0ABF56F43 (id_emprunt_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE incident_emprunt ADD CONSTRAINT FK_918A6B0ABF56F43 FOREIGN KEY (id_emprunt_id) REFERENCES emprunt (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE incident_emprunt');
    }
}
