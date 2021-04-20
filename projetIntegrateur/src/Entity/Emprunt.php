<?php

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
    private $Qte;

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




    public function __construct()
    {
        $this->cycleVieEmprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQte(): ?int
    {
        return $this->Qte;
    }

    public function setQte(int $Qte): self
    {
        $this->Qte = $Qte;

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
}
