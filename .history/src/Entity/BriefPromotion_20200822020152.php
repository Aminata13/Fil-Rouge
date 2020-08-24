<?php

namespace App\Entity;

use App\Repository\BriefPromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BriefPromotionRepository::class)
 */
class BriefPromotion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=LivrablePartiel::class, mappedBy="briefPromotion")
     */
    private $livrablePartiel;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="briefPromotions")
     */
    private $promotion;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="briefPromotions")
     */
    private $brief;

    /**
     * @ORM\ManyToOne(targetEntity=StatutBrief::class, inversedBy="briefPromotions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=BriefApprenant::class, mappedBy="briefPromotion")
     */
    private $briefApprenants;

    public function __construct()
    {
        $this->livrablePartiel = new ArrayCollection();
        $this->briefApprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|LivrablePartiel[]
     */
    public function getLivrablePartiel(): Collection
    {
        return $this->livrablePartiel;
    }

    public function addLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if (!$this->livrablePartiel->contains($livrablePartiel)) {
            $this->livrablePartiel[] = $livrablePartiel;
            $livrablePartiel->setBriefPromotion($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if ($this->livrablePartiel->contains($livrablePartiel)) {
            $this->livrablePartiel->removeElement($livrablePartiel);
            // set the owning side to null (unless already changed)
            if ($livrablePartiel->getBriefPromotion() === $this) {
                $livrablePartiel->setBriefPromotion(null);
            }
        }

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }

    public function getStatut(): ?StatutBrief
    {
        return $this->statut;
    }

    public function setStatut(?StatutBrief $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|BriefApprenant[]
     */
    public function getBriefApprenants(): Collection
    {
        return $this->briefApprenants;
    }

    public function addBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if (!$this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants[] = $briefApprenant;
            $briefApprenant->setBriefPromotion($this);
        }

        return $this;
    }

    public function removeBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if ($this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants->removeElement($briefApprenant);
            // set the owning side to null (unless already changed)
            if ($briefApprenant->getBriefPromotion() === $this) {
                $briefApprenant->setBriefPromotion(null);
            }
        }

        return $this;
    }
}
