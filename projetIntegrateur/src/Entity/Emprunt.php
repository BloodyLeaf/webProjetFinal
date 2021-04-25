<?php
/****************************************
   Fichier :Emprunt.php
   Auteur : Samuel Fournier, Olivier Vigneault, William Goupil, Pier-Alexander Caron
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
   Historique de modifications :
   Date           	Nom               	Description
   =========================================================
 ****************************************/

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmpruntRepository::class)
 */
class Emprunt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $QteInitiale;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDemande;

    /**
     * @ORM\Column(type="date")
     */
    private $dateRetourPrevue;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUtilisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class, inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idPiece;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idSession;

    /**
     * @ORM\ManyToOne(targetEntity=EtatEmprunt::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idEtat;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteActuelle;

    /**
     * @ORM\OneToMany(targetEntity=IncidentEmprunt::class, mappedBy="idEmprunt")
     */
    private $incidentEmprunts;


    

    public function __construct()
    {
        $this->cycleVieEmprunts = new ArrayCollection();
        $this->incidentEmprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQteInitiale(): ?int
    {
        return $this->QteInitiale;
    }

    public function setQteInitiale(int $QteInitiale): self
    {
        $this->QteInitiale = $QteInitiale;

        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTimeInterface $dateDemande): self
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getDateRetourPrevue(): ?\DateTimeInterface
    {
        return $this->dateRetourPrevue;
    }

    public function setDateRetourPrevue(\DateTimeInterface $dateRetourPrevue): self
    {
        $this->dateRetourPrevue = $dateRetourPrevue;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getIdPiece(): ?Piece
    {
        return $this->idPiece;
    }

    public function setIdPiece(?Piece $idPiece): self
    {
        $this->idPiece = $idPiece;

        return $this;
    }

    public function getIdSession(): ?Session
    {
        return $this->idSession;
    }

    public function setIdSession(?Session $idSession): self
    {
        $this->idSession = $idSession;

        return $this;
    }

    public function getIdEtat(): ?EtatEmprunt
    {
        return $this->idEtat;
    }

    public function setIdEtat(?EtatEmprunt $idEtat): self
    {
        $this->idEtat = $idEtat;

        return $this;
    }

    public function getQteActuelle(): ?int
    {
        return $this->qteActuelle;
    }

    public function setQteActuelle(int $qteActuelle): self
    {
        $this->qteActuelle = $qteActuelle;

        return $this;
    }

    /**
     * @return Collection|IncidentEmprunt[]
     */
    public function getIncidentEmprunts(): Collection
    {
        return $this->incidentEmprunts;
    }

    public function addIncidentEmprunt(IncidentEmprunt $incidentEmprunt): self
    {
        if (!$this->incidentEmprunts->contains($incidentEmprunt)) {
            $this->incidentEmprunts[] = $incidentEmprunt;
            $incidentEmprunt->setIdEmprunt($this);
        }

        return $this;
    }

    public function removeIncidentEmprunt(IncidentEmprunt $incidentEmprunt): self
    {
        if ($this->incidentEmprunts->removeElement($incidentEmprunt)) {
            // set the owning side to null (unless already changed)
            if ($incidentEmprunt->getIdEmprunt() === $this) {
                $incidentEmprunt->setIdEmprunt(null);
            }
        }

        return $this;
    }

    public function jetatEmprunt($idState,$nomState){
        return [
            'id' => $this->getId(),
            'idState' => $idState,
            'nomState' => $nomState
        ];
    }

}
