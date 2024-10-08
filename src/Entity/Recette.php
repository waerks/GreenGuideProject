<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $conseil = null;

    #[ORM\Column]
    private ?int $nombreDePersonne = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $ingredients = [];

    #[ORM\Column]
    private ?int $tempsDePreparation = null;

    #[ORM\Column]
    private ?int $tempsDeCuisson = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $etapes = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getConseil(): ?string
    {
        return $this->conseil;
    }

    public function setConseil(?string $conseil): static
    {
        $this->conseil = $conseil;

        return $this;
    }

    public function getNombreDePersonne(): ?int
    {
        return $this->nombreDePersonne;
    }

    public function setNombreDePersonne(int $nombreDePersonne): static
    {
        $this->nombreDePersonne = $nombreDePersonne;

        return $this;
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function setIngredients(array $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getTempsDePreparation(): ?int
    {
        return $this->tempsDePreparation;
    }

    public function setTempsDePreparation(int $tempsDePreparation): static
    {
        $this->tempsDePreparation = $tempsDePreparation;

        return $this;
    }

    public function getTempsDeCuisson(): ?int
    {
        return $this->tempsDeCuisson;
    }

    public function setTempsDeCuisson(int $tempsDeCuisson): static
    {
        $this->tempsDeCuisson = $tempsDeCuisson;

        return $this;
    }

    public function getEtapes(): array
    {
        return $this->etapes;
    }

    public function setEtapes(array $etapes): static
    {
        $this->etapes = $etapes;

        return $this;
    }
}
