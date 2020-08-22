<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LangueRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=LangueRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le libelle des langues doit être unique."
 * )
 * @ApiResource()
 */
class Langue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"bref:","groupe:read","promo_groupe_apprenants:read","promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libelle est obligatoire.")
     * @Groups({"groupe:read","promo_groupe_apprenants:read","promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Promotion::class, mappedBy="langue")
     */
    private $promotions;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="langue")
     */
    private $briefs;

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->setLangue($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->contains($promotion)) {
            $this->promotions->removeElement($promotion);
            // set the owning side to null (unless already changed)
            if ($promotion->getLangue() === $this) {
                $promotion->setLangue(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->setLangue($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            // set the owning side to null (unless already changed)
            if ($brief->getLangue() === $this) {
                $brief->setLangue(null);
            }
        }

        return $this;
    }
}
