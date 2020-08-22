<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  normalizationContext={"groups"={"referentiel:read_all"}},
 *  collectionOperations={
 *      "get"={
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"referentiel:read"}}
 *      },
 *      "getByCompetences"={
 *          "method"="GET",
 *          "path"="/referentiels/groupe_competences",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *      },
 *      "post_referentiel"={
 *         "method"="POST",
 *         "path"="/referentiels",
 *         "controller"=AddReferentiel::class,
 *         "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *         "route_name"="add_referentiel",
 *         "denormalization_context"={"groups"={"referentiel:write"}}
 *     }
 *  },
 *  itemOperations={
 *      "get_referentiel_id"={
 *          "method"="GET",
 *          "path"="/referentiels/{id}",
 *          "defaults"={"id"=null}
 *      },
 *      "get_groupe_referentiel_id"={
 *          "method"="GET",
 *          "path"="/referentiels/{id_referentiel}/groupe_competences/{id_groupe}",
 *          "controller"=ShowGroupeByReferentiel::class,
 *          "route_name"="show_groupe_referentiel_id",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT') or is_granted('ROLE_CM'))"
 *      },
 *      "put_referentiel"={
 *         "method"="PUT",
 *         "path"="/referentiels/{id}",
 *         "controller"=EditReferentielController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *         "route_name"="edit_referentiel",
 *         "denormalization_context"={"groups"={"referentiel:write_all"}}
 *     }
 *  }
 * )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 */
class Referentiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"briefGroupe:read","groupe:read","promo_groupe_apprenants:read","referentiel:read","referentiel:read_all","promotion:read_all","promotion:read_all_ref"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"briefGroupe:read","promotion:read","groupe:read","referentiel:write","promo_groupe_apprenants:read","referentiel:read","referentiel:read_all","promotion:read_all","promotion:read_all_ref"})
     * @Assert\NotBlank(message="Le libelle est requis.")
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"promotion:read","groupe:read","referentiel:write","promo_groupe_apprenants:read","referentiel:read","referentiel:read_all","promotion:read_all","promotion:read_all_ref"})
     * @Assert\NotBlank(message="La présentation est requise.")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=CritereAdmission::class, mappedBy="referentiel", orphanRemoval=true, cascade={"persist"})
     * @Groups({"promotion:read","groupe:read","promo_groupe_apprenants:read","referentiel:read","referentiel:read_all","promotion:read_all","promotion:read_all_ref"})
     */
    private $critereAdmissions;

    /**
     * @ORM\OneToMany(targetEntity=CritereEvaluation::class, mappedBy="referentiel", orphanRemoval=true, cascade={"persist"})
     * @Groups({"promotion:read","groupe:read","promo_groupe_apprenants:read","referentiel:read","referentiel:read_all","promotion:read_all","promotion:read_all_ref"})
     */
    private $critereEvaluations;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     * @Groups({"referentiel:read","referentiel:read_all","promotion:read_all_ref"})
     */
    private $groupeCompetences;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"groupe:read","referentiel:read","referentiel:write","promo_groupe_apprenants:read","promotion:read_all_ref"})
     */
    private $programme;

    /**
     * @ORM\ManyToMany(targetEntity=Promotion::class, mappedBy="referentiels")
     */
    private $promotions;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"promotion:read"})
     */
    private $deleted=false;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="referentiel", orphanRemoval=true)
     */
    private $briefs;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValide::class, mappedBy="referentiel", orphanRemoval=true)
     */
    private $competenceValides;

    public function __construct()
    {
        $this->critereAdmissions = new ArrayCollection();
        $this->critereEvaluations = new ArrayCollection();
        $this->groupeCompetences = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|CritereAdmission[]
     */
    public function getCritereAdmissions(): Collection
    {
        return $this->critereAdmissions;
    }

    public function addCritereAdmission(CritereAdmission $critereAdmission): self
    {
        if (!$this->critereAdmissions->contains($critereAdmission)) {
            $this->critereAdmissions[] = $critereAdmission;
            $critereAdmission->setReferentiel($this);
        }

        return $this;
    }

    public function removeCritereAdmission(CritereAdmission $critereAdmission): self
    {
        if ($this->critereAdmissions->contains($critereAdmission)) {
            $this->critereAdmissions->removeElement($critereAdmission);
            // set the owning side to null (unless already changed)
            if ($critereAdmission->getReferentiel() === $this) {
                $critereAdmission->setReferentiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CritereEvaluation[]
     */
    public function getCritereEvaluations(): Collection
    {
        return $this->critereEvaluations;
    }

    public function addCritereEvaluation(CritereEvaluation $critereEvaluation): self
    {
        if (!$this->critereEvaluations->contains($critereEvaluation)) {
            $this->critereEvaluations[] = $critereEvaluation;
            $critereEvaluation->setReferentiel($this);
        }

        return $this;
    }

    public function removeCritereEvaluation(CritereEvaluation $critereEvaluation): self
    {
        if ($this->critereEvaluations->contains($critereEvaluation)) {
            $this->critereEvaluations->removeElement($critereEvaluation);
            // set the owning side to null (unless already changed)
            if ($critereEvaluation->getReferentiel() === $this) {
                $critereEvaluation->setReferentiel(null);
            }
        }

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
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
        }

        return $this;
    }

    public function getProgramme()
    {
        //return stream_get_contents($this->programme);
        return $this->programme!=null?stream_get_contents($this->programme):null;
        // return $this->programme;
    }

    public function setProgramme($programme): self
    {
        $this->programme = base64_encode($programme);
        // $this->programme = $programme;

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
            $promotion->addReferentiel($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->contains($promotion)) {
            $this->promotions->removeElement($promotion);
            $promotion->removeReferentiel($this);
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
            $brief->setReferentiel($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            // set the owning side to null (unless already changed)
            if ($brief->getReferentiel() === $this) {
                $brief->setReferentiel(null);
            }
        }

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
            $competenceValide->setReferentiel($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValide $competenceValide): self
    {
        if ($this->competenceValides->contains($competenceValide)) {
            $this->competenceValides->removeElement($competenceValide);
            // set the owning side to null (unless already changed)
            if ($competenceValide->getReferentiel() === $this) {
                $competenceValide->setReferentiel(null);
            }
        }

        return $this;
    }
}
