<?php

namespace App\Entity;

use App\Repository\BDVersionRepository;
use Doctrine\ORM\Mapping as ORM;

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
