<?php

namespace App\Entity;

use App\Repository\IncidentEmpruntRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IncidentEmpruntRepository::class)
 */
class IncidentEmprunt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     */
    private $Qte;

    /**
     * @ORM\OneToOne(targetEntity=Emprunt::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $idEmprunt;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getQte(): ?int
    {
        return $this->Qte;
    }

    public function setQte(int $Qte): self
    {
        $this->Qte = $Qte;

        return $this;
    }

    public function getIdEmprunt(): ?Emprunt
    {
        return $this->idEmprunt;
    }

    public function setIdEmprunt(Emprunt $idEmprunt): self
    {
        $this->idEmprunt = $idEmprunt;

        return $this;
    }



}