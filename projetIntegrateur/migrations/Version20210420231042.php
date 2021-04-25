<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420231042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cycle_vie_emprunt (id INT AUTO_INCREMENT NOT NULL, id_etat_id INT NOT NULL, id_emprunt_id INT NOT NULL, date_changement_etat DATETIME NOT NULL, INDEX IDX_496EE17CD3C32F8F (id_etat_id), INDEX IDX_496EE17CBF56F43 (id_emprunt_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cycle_vie_emprunt ADD CONSTRAINT FK_496EE17CD3C32F8F FOREIGN KEY (id_etat_id) REFERENCES etat_emprunt (id)');
        $this->addSql('ALTER TABLE cycle_vie_emprunt ADD CONSTRAINT FK_496EE17CBF56F43 FOREIGN KEY (id_emprunt_id) REFERENCES emprunt (id)');
        $this->addSql('ALTER TABLE emprunt DROP id_etat_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cycle_vie_emprunt');
        $this->addSql('ALTER TABLE emprunt ADD id_etat_id INT NOT NULL');
    }
}
