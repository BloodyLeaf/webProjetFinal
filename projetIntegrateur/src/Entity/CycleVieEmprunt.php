<?php

namespace App\Entity;

use App\Repository\CycleVieEmpruntRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CycleVieEmpruntRepository::class)
 */
class CycleVieEmprunt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateChangementEtat;

    /**
     * @ORM\ManyToOne(targetEntity=EtatEmprunt::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idEtat;

    /**
     * @ORM\ManyToOne(targetEntity=Emprunt::class, inversedBy="cycleVieEmprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idEmprunt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateChangementEtat(): ?\DateTimeInterface
    {
        return $this->dateChangementEtat;
    }

    public function setDateChangementEtat(\DateTimeInterface $dateChangementEtat): self
    {
        $this->dateChangementEtat = $dateChangementEtat;

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

    public function getIdEmprunt(): ?Emprunt
    {
        return $this->idEmprunt;
    }

    public function setIdEmprunt(?Emprunt $idEmprunt): self
    {
        $this->idEmprunt = $idEmprunt;

        return $this;
    }
}
