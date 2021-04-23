<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210422181826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cycle_vie_emprunt');
        $this->addSql('ALTER TABLE emprunt ADD qte_initiale INT NOT NULL, ADD qte_actuelle INT NOT NULL, CHANGE qte id_etat_id INT NOT NULL');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7D3C32F8F FOREIGN KEY (id_etat_id) REFERENCES etat_emprunt (id)');
        $this->addSql('CREATE INDEX IDX_364071D7D3C32F8F ON emprunt (id_etat_id)');
        $this->addSql('ALTER TABLE incident_emprunt DROP INDEX UNIQ_918A6B0ABF56F43, ADD INDEX IDX_918A6B0ABF56F43 (id_emprunt_id)');
        $this->addSql('ALTER TABLE incident_emprunt CHANGE qte qte INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cycle_vie_emprunt (id INT AUTO_INCREMENT NOT NULL, id_etat_id INT NOT NULL, id_emprunt_id INT NOT NULL, date_changement_etat DATETIME NOT NULL, INDEX IDX_496EE17CD3C32F8F (id_etat_id), INDEX IDX_496EE17CBF56F43 (id_emprunt_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cycle_vie_emprunt ADD CONSTRAINT FK_496EE17CBF56F43 FOREIGN KEY (id_emprunt_id) REFERENCES emprunt (id)');
        $this->addSql('ALTER TABLE cycle_vie_emprunt ADD CONSTRAINT FK_496EE17CD3C32F8F FOREIGN KEY (id_etat_id) REFERENCES etat_emprunt (id)');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7D3C32F8F');
        $this->addSql('DROP INDEX IDX_364071D7D3C32F8F ON emprunt');
        $this->addSql('ALTER TABLE emprunt ADD qte INT NOT NULL, DROP id_etat_id, DROP qte_initiale, DROP qte_actuelle');
        $this->addSql('ALTER TABLE incident_emprunt DROP INDEX IDX_918A6B0ABF56F43, ADD UNIQUE INDEX UNIQ_918A6B0ABF56F43 (id_emprunt_id)');
        $this->addSql('ALTER TABLE incident_emprunt CHANGE qte qte SMALLINT NOT NULL');
    }
}
