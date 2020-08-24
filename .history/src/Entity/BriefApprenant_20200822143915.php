<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BriefApprenantRepository;

/**
 * @ORM\Entity(repositoryClass=BriefApprenantRepository::class)
 * @ApiResource()
 */
class BriefApprenant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=StatutBrief::class, inversedBy="briefApprenants")
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=BriefPromotion::class, inversedBy="briefApprenants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $briefPromotion;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="briefApprenants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $apprenant;

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

    public function getBriefPromotion(): ?BriefPromotion
    {
        return $this->briefPromotion;
    }

    public function setBriefPromotion(?BriefPromotion $briefPromotion): self
    {
        $this->briefPromotion = $briefPromotion;

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
}
