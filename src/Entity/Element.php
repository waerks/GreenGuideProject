<?php

namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'elementAmi')]
    #[ORM\JoinTable(name: 'element_ami')]
    private Collection $ami;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'ami')]
    private Collection $elementAmi;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'elementEnnemi')]
    #[ORM\JoinTable(name: 'element_ennemi')]
    private Collection $ennemi;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'ennemi')]
    private Collection $elementEnnemi;

    /**
     * @var Collection<int, TypeElement>
     */
    #[ORM\ManyToMany(targetEntity: TypeElement::class, inversedBy: 'elements')]
    private Collection $typeElement;

    /**
     * @var Collection<int, Etape>
     */
    #[ORM\OneToMany(targetEntity: Etape::class, mappedBy: 'element', orphanRemoval: true)]
    private Collection $etape;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $benefices = null;

    public function __construct()
    {
        $this->ami = new ArrayCollection();
        $this->elementAmi = new ArrayCollection();
        $this->ennemi = new ArrayCollection();
        $this->elementEnnemi = new ArrayCollection();
        $this->typeElement = new ArrayCollection();
        $this->etape = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, self>
     */
    public function getAmi(): Collection
    {
        return $this->ami;
    }

    public function addAmi(self $ami): static
    {
        if (!$this->ami->contains($ami)) {
            $this->ami->add($ami);
        }

        return $this;
    }

    public function removeAmi(self $ami): static
    {
        $this->ami->removeElement($ami);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getElementAmi(): Collection
    {
        return $this->elementAmi;
    }

    public function addElementAmi(self $elementAmi): static
    {
        if (!$this->elementAmi->contains($elementAmi)) {
            $this->elementAmi->add($elementAmi);
            $elementAmi->addAmi($this);
        }

        return $this;
    }

    public function removeElementAmi(self $elementAmi): static
    {
        if ($this->elementAmi->removeElement($elementAmi)) {
            $elementAmi->removeAmi($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getEnnemi(): Collection
    {
        return $this->ennemi;
    }

    public function addEnnemi(self $ennemi): static
    {
        if (!$this->ennemi->contains($ennemi)) {
            $this->ennemi->add($ennemi);
        }

        return $this;
    }

    public function removeEnnemi(self $ennemi): static
    {
        $this->ennemi->removeElement($ennemi);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getElementEnnemi(): Collection
    {
        return $this->elementEnnemi;
    }

    public function addElementEnnemi(self $elementEnnemi): static
    {
        if (!$this->elementEnnemi->contains($elementEnnemi)) {
            $this->elementEnnemi->add($elementEnnemi);
            $elementEnnemi->addEnnemi($this);
        }

        return $this;
    }

    public function removeElementEnnemi(self $elementEnnemi): static
    {
        if ($this->elementEnnemi->removeElement($elementEnnemi)) {
            $elementEnnemi->removeEnnemi($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TypeElement>
     */
    public function getTypeElement(): Collection
    {
        return $this->typeElement;
    }

    public function addTypeElement(TypeElement $typeElement): static
    {
        if (!$this->typeElement->contains($typeElement)) {
            $this->typeElement->add($typeElement);
        }

        return $this;
    }

    public function removeTypeElement(TypeElement $typeElement): static
    {
        $this->typeElement->removeElement($typeElement);

        return $this;
    }

    /**
     * @return Collection<int, Etape>
     */
    public function getEtape(): Collection
    {
        return $this->etape;
    }

    public function addEtape(Etape $etape): static
    {
        if (!$this->etape->contains($etape)) {
            $this->etape->add($etape);
            $etape->setElement($this);
        }

        return $this;
    }

    public function removeEtape(Etape $etape): static
    {
        if ($this->etape->removeElement($etape)) {
            // set the owning side to null (unless already changed)
            if ($etape->getElement() === $this) {
                $etape->setElement(null);
            }
        }

        return $this;
    }

    public function getBenefices(): ?string
    {
        return $this->benefices;
    }

    public function setBenefices(string $benefices): static
    {
        $this->benefices = $benefices;

        return $this;
    }
}
