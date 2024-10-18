<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentaireRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'commentairesParents')]
    private ?self $commentaireParent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'commentaireParent')]
    private Collection $commentairesParents;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'reponse')]
    private ?self $commentaire = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'commentaire')]
    private Collection $reponse;

    public function __construct()
    {
        $this->commentairesParents = new ArrayCollection();
        $this->reponse = new ArrayCollection();
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function generateSlug(SluggerInterface $slugger): void
    {
        $this->slug = $slugger->slug($this->titre)->lower();
    }

    public function getCommentaireParent(): ?self
    {
        return $this->commentaireParent;
    }

    public function setCommentaireParent(?self $commentaireParent): static
    {
        $this->commentaireParent = $commentaireParent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCommentairesParents(): Collection
    {
        return $this->commentairesParents;
    }

    public function addCommentairesParent(self $commentairesParent): static
    {
        if (!$this->commentairesParents->contains($commentairesParent)) {
            $this->commentairesParents->add($commentairesParent);
            $commentairesParent->setCommentaireParent($this);
        }

        return $this;
    }

    public function removeCommentairesParent(self $commentairesParent): static
    {
        if ($this->commentairesParents->removeElement($commentairesParent)) {
            // set the owning side to null (unless already changed)
            if ($commentairesParent->getCommentaireParent() === $this) {
                $commentairesParent->setCommentaireParent(null);
            }
        }

        return $this;
    }

    public function getCommentaire(): ?self
    {
        return $this->commentaire;
    }

    public function setCommentaire(?self $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getReponse(): Collection
    {
        return $this->reponse;
    }

    public function addReponse(self $reponse): static
    {
        if (!$this->reponse->contains($reponse)) {
            $this->reponse->add($reponse);
            $reponse->setCommentaire($this);
        }

        return $this;
    }

    public function removeReponse(self $reponse): static
    {
        if ($this->reponse->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getCommentaire() === $this) {
                $reponse->setCommentaire(null);
            }
        }

        return $this;
    }
}
