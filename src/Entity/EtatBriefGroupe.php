<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EtatBriefGroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EtatBriefGroupeRepository::class)
 * @ApiResource()
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
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="etatBriefGroupes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brief;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="etatBriefGroupes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupe;

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

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

}
