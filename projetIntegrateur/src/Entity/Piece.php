<?php

namespace App\Entity;

use App\Repository\PieceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PieceRepository::class)
 */
class Piece
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     */
    private $QteTotal;

    /**
     * @ORM\Column(type="smallint")
     */
    private $QteEmprunter;

    /**
     * @ORM\Column(type="smallint")
     */
    private $QteBrise;

    /**
     * @ORM\Column(type="smallint")
     */
    private $QtePerdu;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="pieces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCategorie;

    /**
     * @ORM\OneToMany(targetEntity=Emprunt::class, mappedBy="idPiece")
     */
    private $emprunts;

    public function __construct()
    {
        $this->emprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getQteTotal(): ?int
    {
        return $this->QteTotal;
    }

    public function setQteTotal(int $QteTotal): self
    {
        $this->QteTotal = $QteTotal;

        return $this;
    }

    public function getQteEmprunter(): ?int
    {
        return $this->QteEmprunter;
    }

    public function setQteEmprunter(int $QteEmprunter): self
    {
        $this->QteEmprunter = $QteEmprunter;

        return $this;
    }

    public function getQteBrise(): ?int
    {
        return $this->QteBrise;
    }

    public function setQteBrise(int $QteBrise): self
    {
        $this->QteBrise = $QteBrise;

        return $this;
    }

    public function getQtePerdu(): ?int
    {
        return $this->QtePerdu;
    }

    public function setQtePerdu(int $QtePerdu): self
    {
        $this->QtePerdu = $QtePerdu;

        return $this;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }

    /**
     * @return Collection|Emprunt[]
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): self
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts[] = $emprunt;
            $emprunt->setIdPiece($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): self
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getIdPiece() === $this) {
                $emprunt->setIdPiece(null);
            }
        }

        return $this;
    }
}
