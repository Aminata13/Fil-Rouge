<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivrableRenduRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivrableRenduRepository::class)
 * @ApiResource()
 */
class LivrableRendu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $dateRendu;

    /**
     * @ORM\ManyToOne(targetEntity=StatutLivrable::class, inversedBy="livrableRendus")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $statut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $delai;

    /**
     * @ORM\ManyToOne(targetEntity=LivrablePartiel::class, inversedBy="livrableRendus")
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $livrablePartiel;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="livrableRendus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $apprenant;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="livrableRendu")
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRendu(): ?\DateTimeInterface
    {
        return $this->dateRendu;
    }

    public function setDateRendu(\DateTimeInterface $dateRendu): self
    {
        $this->dateRendu = $dateRendu;

        return $this;
    }

    public function getStatut(): ?StatutLivrable
    {
        return $this->statut;
    }

    public function setStatut(?StatutLivrable $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(?\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getLivrablePartiel(): ?LivrablePartiel
    {
        return $this->livrablePartiel;
    }

    public function setLivrablePartiel(?LivrablePartiel $livrablePartiel): self
    {
        $this->livrablePartiel = $livrablePartiel;

        return $this;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setLivrableRendu($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getLivrableRendu() === $this) {
                $commentaire->setLivrableRendu(null);
            }
        }

        return $this;
    }
}
