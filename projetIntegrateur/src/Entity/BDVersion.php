<?php
/****************************************
   Fichier : BDVersion.php
   Auteur : Samuel Fournier, Olivier Vigneault, William Goupil, Pier-Alexander Caron
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
   Historique de modifications :
   Date           	Nom               	Description
   =========================================================
   19 avril 2021    Samuel Olivier      Création initiale
 ****************************************/

namespace App\Entity;

use App\Repository\BDVersionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\DateTime;
/**
 * @ORM\Entity(repositoryClass=BDVersionRepository::class)
 */
class BDVersion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $tableModifier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getTableModifier(): ?string
    {
        return $this->tableModifier;
    }

    public function setTableModifier(string $tableModifier): self
    {
        $this->tableModifier = $tableModifier;

        return $this;
    }
}
