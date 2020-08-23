<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetenceValideRepository;

/**
 * @ORM\Entity(repositoryClass=CompetenceValideRepository::class)
 * @ApiResource()
 */
class CompetenceValide
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"apprenant_competence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"apprenant_competence:read"})
     */
    private $niveau1;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"apprenant_competence:read"})
     */
    private $niveau2;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"apprenant_competence:read"})
     */
    private $niveau3;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="competenceValides")
     * @ORM\JoinColumn(nullable=false)
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="competenceValides")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentiel;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="competenceValides")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"apprenant_competence:read"})
     */
    private $competence;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="competenceValides")
     */
    private $promotion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveau1(): ?bool
    {
        return $this->niveau1;
    }

    public function setNiveau1(bool $niveau1): self
    {
        $this->niveau1 = $niveau1;

        return $this;
    }

    public function getNiveau2(): ?bool
    {
        return $this->niveau2;
    }

    public function setNiveau2(bool $niveau2): self
    {
        $this->niveau2 = $niveau2;

        return $this;
    }

    public function getNiveau3(): ?bool
    {
        return $this->niveau3;
    }

    public function setNiveau3(bool $niveau3): self
    {
        $this->niveau3 = $niveau3;

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

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

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
}
