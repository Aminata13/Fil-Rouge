<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NiveauEvaluationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  itemOperations={
 *      "put","get"
 *  }
 * )
 * @ORM\Entity(repositoryClass=NiveauEvaluationRepository::class)
 */
class NiveauEvaluation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"grpcompetence:read_all","competence:read_all"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpcompetence:read_all","competence:read_all",v})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"grpcompetence:read_all","competence:read_all"})
     */
    private $groupeAction;

    /**
     * @ORM\Column(type="text")
     * @Groups({"grpcompetence:read_all","competence:read_all"})
     */
    private $critereEvaluation;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveaux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $competence;

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

    public function getGroupeAction(): ?string
    {
        return $this->groupeAction;
    }

    public function setGroupeAction(string $groupeAction): self
    {
        $this->groupeAction = $groupeAction;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

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
}
