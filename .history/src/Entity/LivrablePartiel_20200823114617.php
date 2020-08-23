<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrablePartielRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=LivrablePartielRepository::class)
 * @ApiResource(
 *  collectionOperations={
 *     "get_competences_by_apprenant"={
 *         "method"="GET",
 *         "path"="/formateurs/promo/id/referentiel/id/competences",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *         "route_name"="show_competences_by_apprenant"
 *     },
 *     "get_competences_by_apprenant_id"={
 *         "method"="GET",
 *         "path"="/apprenant/id/promo/id/referentiel/id/competences",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="(is_granted('ROLE_APPRENANT'))",
 *         "route_name"="show_competences_by_apprenant_id"
 *     },
 *     "get_statistiques_by_apprenant_id"={
 *         "method"="GET",
 *         "path"="/apprenants/id/promo/id/referentiel/id/statistiques/briefs",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="(is_granted('ROLE_APPRENANT'))",
 *         "route_name"="show_statistiques_by_apprenant_id"
 *     },
 *     "get_statistiques_by_competences"={
 *         "method"="GET",
 *         "path"="/formateurs/promo/id/referentiel/id/statistiques/competences",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *         "route_name"="show_statistiques_by_competences"
 *     },
 *     "get_commentaires_by_livrablePartiel"={
 *         "method"="GET",
 *         "path"="/formateurs/livrablepartiels/id/commentaires",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT))",
 *         "route_name"="show_commentaires_by_livrablePartiel"
 *     },
 *     "add_commentaire_by_formateur"={
 *         "method"="POST",
 *         "path"="/formateurs/livrablepartiels/id/commentaires",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="(is_granted('ROLE_FORMATEUR'))",
 *         "route_name"="post_commentaire_by_formateur"
 *     }, 
 *     "add_commentaire_by_apprenant"={
 *         "method"="POST",
 *         "path"="/apprenants/livrablepartiels/id/commentaires",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="(is_granted('ROLE_APPRENANT'))",
 *         "route_name"="post_commentaire_by_apprenant"
 *     },
 *     "add_livrable_partiel_by_formateur"={
 *         "method"="PUT",
 *         "path"="/formateurs/promo/id/brief/id/livrablepartiels",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *         "route_name"="put_livrable_partiel_by_formateur"
 *     },
 *     "add_statut_by_apprenant"={
 *         "method"="PUT",
 *         "path"="/apprenants/id/livrablepartiels/id",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="( is_granted('ROLE_FORMATEUR'))",
 *         "route_name"="put_statut_by_apprenant"
 *     }
 *  }
 * )
 */
class LivrablePartiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAffectation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSoumission;

    /**
     * @ORM\ManyToMany(targetEntity=NiveauEvaluation::class, inversedBy="livrablePartiels")
     */
    private $niveauCompetences;

    /**
     * @ORM\OneToMany(targetEntity=LivrableRendu::class, mappedBy="livrablePartiel")
     */
    private $livrableRendus;

    /**
     * @ORM\ManyToOne(targetEntity=BriefPromotion::class, inversedBy="livrablePartiel")
     * @ORM\JoinColumn(nullable=false)
     */
    private $briefPromotion;

    public function __construct()
    {
        $this->niveauCompetences = new ArrayCollection();
        $this->livrableRendus = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDateAffectation(): ?\DateTimeInterface
    {
        return $this->dateAffectation;
    }

    public function setDateAffectation(\DateTimeInterface $dateAffectation): self
    {
        $this->dateAffectation = $dateAffectation;

        return $this;
    }

    public function getDateSoumission(): ?\DateTimeInterface
    {
        return $this->dateSoumission;
    }

    public function setDateSoumission(\DateTimeInterface $dateSoumission): self
    {
        $this->dateSoumission = $dateSoumission;

        return $this;
    }

    /**
     * @return Collection|NiveauEvaluation[]
     */
    public function getNiveauCompetences(): Collection
    {
        return $this->niveauCompetences;
    }

    public function addNiveauCompetence(NiveauEvaluation $niveauCompetence): self
    {
        if (!$this->niveauCompetences->contains($niveauCompetence)) {
            $this->niveauCompetences[] = $niveauCompetence;
        }

        return $this;
    }

    public function removeNiveauCompetence(NiveauEvaluation $niveauCompetence): self
    {
        if ($this->niveauCompetences->contains($niveauCompetence)) {
            $this->niveauCompetences->removeElement($niveauCompetence);
        }

        return $this;
    }

    /**
     * @return Collection|LivrableRendu[]
     */
    public function getLivrableRendus(): Collection
    {
        return $this->livrableRendus;
    }

    public function addLivrableRendu(LivrableRendu $livrableRendu): self
    {
        if (!$this->livrableRendus->contains($livrableRendu)) {
            $this->livrableRendus[] = $livrableRendu;
            $livrableRendu->setLivrablePartiel($this);
        }

        return $this;
    }

    public function removeLivrableRendu(LivrableRendu $livrableRendu): self
    {
        if ($this->livrableRendus->contains($livrableRendu)) {
            $this->livrableRendus->removeElement($livrableRendu);
            // set the owning side to null (unless already changed)
            if ($livrableRendu->getLivrablePartiel() === $this) {
                $livrableRendu->setLivrablePartiel(null);
            }
        }

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
}
