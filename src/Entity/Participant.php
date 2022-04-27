<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    public $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    public $prenom;

    /**
     * @ORM\Column(type="float")
     */
    public $montant;

    /**
     * @ORM\ManyToOne(targetEntity=Soiree::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $soiree;

    /**
     * @ORM\Column(type="integer")
     */
    public $a_faire = 0;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getSoiree(): ?Soiree
    {
        return $this->soiree;
    }

    public function setSoiree(?Soiree $soiree): self
    {
        $this->soiree = $soiree;

        return $this;
    }

    public function __toString()
    {
        return $this->nom." ".$this->prenom;
    }

    public function getA_faire(): ?int
    {
        return $this->a_faire;
    }

    public function setA_faire(int $a_faire): self
    {
        $this->a_faire = $a_faire;

        return $this;
    }
}
