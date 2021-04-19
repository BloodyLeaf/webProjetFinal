<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
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
    private $annee;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFinSession;

    /**
     * @ORM\ManyToOne(targetEntity=saison::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idSaison;

    /**
     * @ORM\OneToMany(targetEntity=Emprunt::class, mappedBy="idSession")
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

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getDateFinSession(): ?\DateTimeInterface
    {
        return $this->dateFinSession;
    }

    public function setDateFinSession(\DateTimeInterface $dateFinSession): self
    {
        $this->dateFinSession = $dateFinSession;

        return $this;
    }

    public function getIdSaison(): ?saison
    {
        return $this->idSaison;
    }

    public function setIdSaison(?saison $idSaison): self
    {
        $this->idSaison = $idSaison;

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
            $emprunt->setIdSession($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): self
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getIdSession() === $this) {
                $emprunt->setIdSession(null);
            }
        }

        return $this;
    }
}
