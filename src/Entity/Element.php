<?php

namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElementRepository::class)]
class Element
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 150)]
    private ?string $nomScientifique = null;

    #[ORM\Column(length: 150)]
    private ?string $famille = null;

    #[ORM\Column(length: 255)]
    private ?string $hauteur = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 150)]
    private ?string $sol = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $resume = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $entretien = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $rotationDesCultures = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $conservation = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contreIndication = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $informationsNutritionnelles = null;

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

    public function getNomScientifique(): ?string
    {
        return $this->nomScientifique;
    }

    public function setNomScientifique(string $nomScientifique): static
    {
        $this->nomScientifique = $nomScientifique;

        return $this;
    }

    public function getFamille(): ?string
    {
        return $this->famille;
    }

    public function setFamille(string $famille): static
    {
        $this->famille = $famille;

        return $this;
    }

    public function getHauteur(): ?string
    {
        return $this->hauteur;
    }

    public function setHauteur(string $hauteur): static
    {
        $this->hauteur = $hauteur;

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

    public function getSol(): ?string
    {
        return $this->sol;
    }

    public function setSol(string $sol): static
    {
        $this->sol = $sol;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getEntretien(): ?string
    {
        return $this->entretien;
    }

    public function setEntretien(string $entretien): static
    {
        $this->entretien = $entretien;

        return $this;
    }

    public function getRotationDesCultures(): ?string
    {
        return $this->rotationDesCultures;
    }

    public function setRotationDesCultures(string $rotationDesCultures): static
    {
        $this->rotationDesCultures = $rotationDesCultures;

        return $this;
    }

    public function getConservation(): ?string
    {
        return $this->conservation;
    }

    public function setConservation(string $conservation): static
    {
        $this->conservation = $conservation;

        return $this;
    }

    public function getContreIndication(): ?string
    {
        return $this->contreIndication;
    }

    public function setContreIndication(string $contreIndication): static
    {
        $this->contreIndication = $contreIndication;

        return $this;
    }

    public function getInformationsNutritionnelles(): ?string
    {
        return $this->informationsNutritionnelles;
    }

    public function setInformationsNutritionnelles(string $informationsNutritionnelles): static
    {
        $this->informationsNutritionnelles = $informationsNutritionnelles;

        return $this;
    }
}
