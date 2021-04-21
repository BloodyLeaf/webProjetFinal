<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419181601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bdversion (id INT AUTO_INCREMENT NOT NULL, timestamp DATETIME NOT NULL, table_modifier VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cycle_vie_emprunt (id INT AUTO_INCREMENT NOT NULL, id_etat_id INT NOT NULL, id_emprunt_id INT NOT NULL, date_changement_etat DATE NOT NULL, INDEX IDX_496EE17CD3C32F8F (id_etat_id), INDEX IDX_496EE17CBF56F43 (id_emprunt_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, id_utilisateur_id INT NOT NULL, id_piece_id INT NOT NULL, id_session_id INT NOT NULL, qte INT NOT NULL, date_demande DATE NOT NULL, date_retour_prevue DATE NOT NULL, INDEX IDX_364071D7C6EE5C49 (id_utilisateur_id), INDEX IDX_364071D794D4233D (id_piece_id), INDEX IDX_364071D7C4B56C08 (id_session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_emprunt (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incident_emprunt (id INT AUTO_INCREMENT NOT NULL, id_emprunt_id INT NOT NULL, description LONGTEXT NOT NULL, qte SMALLINT NOT NULL, UNIQUE INDEX UNIQ_918A6B0ABF56F43 (id_emprunt_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE piece (id INT AUTO_INCREMENT NOT NULL, id_categorie_id INT NOT NULL, nom VARCHAR(32) NOT NULL, description LONGTEXT NOT NULL, qte_total SMALLINT NOT NULL, qte_emprunter SMALLINT NOT NULL, qte_brise SMALLINT NOT NULL, qte_perdu SMALLINT NOT NULL, INDEX IDX_44CA0B239F34925F (id_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saison (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(16) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, id_saison_id INT NOT NULL, annee INT NOT NULL, date_fin_session DATE NOT NULL, INDEX IDX_D044D5D47950FD6B (id_saison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(32) NOT NULL, prenom VARCHAR(32) NOT NULL, no_groupe SMALLINT NULL, password_reset TINYINT(1) NOT NULL, etat TINYINT(1) NOT NULL, condition_utilisation TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cycle_vie_emprunt ADD CONSTRAINT FK_496EE17CD3C32F8F FOREIGN KEY (id_etat_id) REFERENCES etat_emprunt (id)');
        $this->addSql('ALTER TABLE cycle_vie_emprunt ADD CONSTRAINT FK_496EE17CBF56F43 FOREIGN KEY (id_emprunt_id) REFERENCES emprunt (id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7C6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D794D4233D FOREIGN KEY (id_piece_id) REFERENCES piece (id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7C4B56C08 FOREIGN KEY (id_session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE incident_emprunt ADD CONSTRAINT FK_918A6B0ABF56F43 FOREIGN KEY (id_emprunt_id) REFERENCES emprunt (id)');
        $this->addSql('ALTER TABLE piece ADD CONSTRAINT FK_44CA0B239F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D47950FD6B FOREIGN KEY (id_saison_id) REFERENCES saison (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE piece DROP FOREIGN KEY FK_44CA0B239F34925F');
        $this->addSql('ALTER TABLE cycle_vie_emprunt DROP FOREIGN KEY FK_496EE17CBF56F43');
        $this->addSql('ALTER TABLE incident_emprunt DROP FOREIGN KEY FK_918A6B0ABF56F43');
        $this->addSql('ALTER TABLE cycle_vie_emprunt DROP FOREIGN KEY FK_496EE17CD3C32F8F');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D794D4233D');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D47950FD6B');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7C4B56C08');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7C6EE5C49');
        $this->addSql('DROP TABLE bdversion');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE cycle_vie_emprunt');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE etat_emprunt');
        $this->addSql('DROP TABLE incident_emprunt');
        $this->addSql('DROP TABLE piece');
        $this->addSql('DROP TABLE saison');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE utilisateur');
    }
}
