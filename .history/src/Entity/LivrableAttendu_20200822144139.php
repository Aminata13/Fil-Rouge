<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\LivrableAttenduRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivrableAttenduRepository::class)
 * @ApiResource()
 */
class LivrableAttendu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, inversedBy="livrableAttendus")
     */
    private $briefs;

    /**
     * @ORM\OneToMany(targetEntity=LivrableApprenant::class, mappedBy="livrableAttendu")
     */
    private $livrableApprenants;

    public function __construct()
    {
        $this->briefs = new ArrayCollection();
        $this->livrableApprenants = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
        }

        return $this;
    }

    /**
     * @return Collection|LivrableApprenant[]
     */
    public function getLivrableApprenants(): Collection
    {
        return $this->livrableApprenants;
    }

    public function addLivrableApprenant(LivrableApprenant $livrableApprenant): self
    {
        if (!$this->livrableApprenants->contains($livrableApprenant)) {
            $this->livrableApprenants[] = $livrableApprenant;
            $livrableApprenant->setLivrableAttendu($this);
        }

        return $this;
    }

    public function removeLivrableApprenant(LivrableApprenant $livrableApprenant): self
    {
        if ($this->livrableApprenants->contains($livrableApprenant)) {
            $this->livrableApprenants->removeElement($livrableApprenant);
            // set the owning side to null (unless already changed)
            if ($livrableApprenant->getLivrableAttendu() === $this) {
                $livrableApprenant->setLivrableAttendu(null);
            }
        }

        return $this;
    }
}
