<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\EtatBriefGroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EtatBriefGroupeRepository::class)
 */
class EtatBriefGroupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"briefGroupe:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=StatutBrief::class, inversedBy="etatBriefGroupes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"briefGroupe:read"})
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="etatBriefGroupe")
     */
    private $brief;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="etatBriefGroupe")
     */
    private $groupe;

    public function __construct()
    {
        $this->brief = new ArrayCollection();
        $this->groupe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Brief[]
     */
    public function getBrief(): Collection
    {
        return $this->brief;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->brief->contains($brief)) {
            $this->brief[] = $brief;
            $brief->setEtatBriefGroupe($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->brief->contains($brief)) {
            $this->brief->removeElement($brief);
            // set the owning side to null (unless already changed)
            if ($brief->getEtatBriefGroupe() === $this) {
                $brief->setEtatBriefGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
            $groupe->setEtatBriefGroupe($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupe->contains($groupe)) {
            $this->groupe->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getEtatBriefGroupe() === $this) {
                $groupe->setEtatBriefGroupe(null);
            }
        }

        return $this;
    }
}
