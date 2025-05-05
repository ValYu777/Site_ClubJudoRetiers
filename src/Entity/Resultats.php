<?php

namespace App\Entity;

use App\Repository\ResultatsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultatsRepository::class)]
class Resultats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $competition = null;

    #[ORM\Column(length: 255)]
    private ?string $competiteur = null;

    #[ORM\Column(length: 255)]
    private ?string $resultat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetition(): ?string
    {
        return $this->competition;
    }

    public function setCompetition(string $competition): static
    {
        $this->competition = $competition;

        return $this;
    }

    public function getCompetiteur(): ?string
    {
        return $this->competiteur;
    }

    public function setCompetiteur(string $competiteur): static
    {
        $this->competiteur = $competiteur;

        return $this;
    }

    public function getResultat(): ?string
    {
        return $this->resultat;
    }

    public function setResultat(string $resultat): static
    {
        $this->resultat = $resultat;

        return $this;
    }
}
