<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(type: 'json')]
private array $disciplines = [];

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $prospectus = null;

    #[ORM\Column(length: 7, nullable: true)]
private ?string $color = null;

public function getColor(): ?string
{
    return $this->color;
}

public function setColor(?string $color): static
{
    $this->color = $color;

    return $this;
}


    public function getProspectus(): ?string
    {
        return $this->prospectus;
    }

    public function setProspectus(?string $prospectus): static
    {
        $this->prospectus = $prospectus;

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): static
    {
        $this->end = $end;

        return $this;
    }

    public function getDisciplines(): array
{
    return $this->disciplines;
}

public function setDisciplines(?array $disciplines): static
{
    $this->disciplines = $disciplines ?? [];
    return $this;
}
}
