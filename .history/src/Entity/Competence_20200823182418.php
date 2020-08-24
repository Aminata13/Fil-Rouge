<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le libelle existe déjà."
 * )
 * @ApiResource(
 * routePrefix="/admin",
 *  collectionOperations={
 *      "get"={
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"competence:read_all"}}
 *      },
 *      "post_competence"={
 *         "method"="POST",
 *         "path"="/competences",
 *         "controller"=AddCompetence::class,
 *         "access_control"="(is_granted('ROLE_ADMIN'))",
 *         "route_name"="add_competence",
 *         "denormalization_context"={"groups"={"competence:write"}}
 *     }
 *  },
 *  itemOperations={
 *      "get"={
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"competence:read_all"}}
 *      },
 *      "put_competence"={
 *         "method"="PUT",
 *         "path"="/competences/{id}",
 *         "controller"=EditCompetenceController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN'))",
 *         "route_name"="edit_competence",
 *         "denormalization_context"={"groups"={"competence:write"}}
 *     }
 *  }
 * )
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 */
class Competence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"apprenant_competence:read","brief_livrable_partiel:read","brief:read","grpcompetence:read","grpcompetence:read_all","competence:read_all","referentiel:read_all","promotion:read_all_ref"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenant_competence:read","brief_livrable_partiel:read","briefGroupe:read","brief:read","grpcompetence:read","grpcompetence:read_all","competence:read_all","referentiel:read_all","grpcompetence:write", "competence:write","promotion:read_all_ref"})
     * @Assert\NotBlank(message="le libelle d'une competence est requis.")
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, mappedBy="competences")
     * @Groups({"briefGroupe:read","competence:write"})
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=NiveauEvaluation::class, mappedBy="competence", orphanRemoval=true, cascade={"persist"})
     * @Groups({"apprenant_competence:read","grpcompetence:read_all","competence:read_all", "competence:write","promotion:read_all_ref"})
     */
    private $niveaux;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValide::class, mappedBy="competence", orphanRemoval=true)
     */
    private $competenceValides;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->competenceValides = new ArrayCollection();
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
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
            $groupeCompetence->removeCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|NiveauEvaluation[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(NiveauEvaluation $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(NiveauEvaluation $niveau): self
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return Collection|CompetenceValide[]
     */
    public function getCompetenceValides(): Collection
    {
        return $this->competenceValides;
    }

    public function addCompetenceValide(CompetenceValide $competenceValide): self
    {
        if (!$this->competenceValides->contains($competenceValide)) {
            $this->competenceValides[] = $competenceValide;
            $competenceValide->setCompetence($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValide $competenceValide): self
    {
        if ($this->competenceValides->contains($competenceValide)) {
            $this->competenceValides->removeElement($competenceValide);
            // set the owning side to null (unless already changed)
            if ($competenceValide->getCompetence() === $this) {
                $competenceValide->setCompetence(null);
            }
        }

        return $this;
    }

}
