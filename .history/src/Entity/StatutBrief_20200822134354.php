<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StatutBriefRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StatutBriefRepository::class)
 * @
 */
class StatutBrief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"briefGroupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"briefGroupe:read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=BriefPromotion::class, mappedBy="statut")
     */
    private $briefPromotions;

    /**
     * @ORM\OneToMany(targetEntity=BriefApprenant::class, mappedBy="statut")
     */
    private $briefApprenants;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="statut")
     */
    private $etatBriefGroupes;

    public function __construct()
    {
        $this->briefPromotions = new ArrayCollection();
        $this->briefApprenants = new ArrayCollection();
        $this->etatBriefGroupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|BriefPromotion[]
     */
    public function getBriefPromotions(): Collection
    {
        return $this->briefPromotions;
    }

    public function addBriefPromotion(BriefPromotion $briefPromotion): self
    {
        if (!$this->briefPromotions->contains($briefPromotion)) {
            $this->briefPromotions[] = $briefPromotion;
            $briefPromotion->setStatut($this);
        }

        return $this;
    }

    public function removeBriefPromotion(BriefPromotion $briefPromotion): self
    {
        if ($this->briefPromotions->contains($briefPromotion)) {
            $this->briefPromotions->removeElement($briefPromotion);
            // set the owning side to null (unless already changed)
            if ($briefPromotion->getStatut() === $this) {
                $briefPromotion->setStatut(null);
            }
        }

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
            $briefApprenant->setStatut($this);
        }

        return $this;
    }

    public function removeBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if ($this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants->removeElement($briefApprenant);
            // set the owning side to null (unless already changed)
            if ($briefApprenant->getStatut() === $this) {
                $briefApprenant->setStatut(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EtatBriefGroupe[]
     */
    public function getEtatBriefGroupes(): Collection
    {
        return $this->etatBriefGroupes;
    }

    public function addEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if (!$this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes[] = $etatBriefGroupe;
            $etatBriefGroupe->setStatut($this);
        }

        return $this;
    }

    public function removeEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if ($this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes->removeElement($etatBriefGroupe);
            // set the owning side to null (unless already changed)
            if ($etatBriefGroupe->getStatut() === $this) {
                $etatBriefGroupe->setStatut(null);
            }
        }

        return $this;
    }
}
