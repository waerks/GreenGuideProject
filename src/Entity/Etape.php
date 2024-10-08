<?php

namespace App\Entity;

use App\Repository\EtapeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtapeRepository::class)]
class Etape
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $mois = [];

    #[ORM\Column(length: 255)]
    private ?string $periode = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $instructions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMois(): array
    {
        return $this->mois;
    }

    public function setMois(array $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getPeriode(): ?string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): static
    {
        $this->periode = $periode;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(string $instructions): static
    {
        $this->instructions = $instructions;

        return $this;
    }
}
