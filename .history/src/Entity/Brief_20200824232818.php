<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get"={
 *              "path"="/formateurs/briefs",
 *              "normalization_context"={"groups"={"brief:read"}},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *          },
 *          "get_brief_by_promoId_by_groupe_id"={
 *              "method"="GET",
 *              "path"="/formateurs/promotions/{id_promo}/groupe/{id_groupe}/briefs",
 *              "controller"=BriefController::class,
 *              "route_name"="show_brief_by_promoId_by_groupe_id",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *          },
 *          "get_brief_by_promoId"={
 *              "method"="GET",
 *              "path"="/formateurs/promotions/{id_promo}/briefs",
 *              "controller"=BriefController::class,
 *              "route_name"="show_brief_by_promoId",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *          },
 *          "get_brief_brouillons_formateur"={
 *              "method"="GET",
 *              "path"="/formateurs/{id}/briefs/brouillons",
 *              "controller"=BriefController::class,
 *              "route_name"="show_brief_brouillons_formateur",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))"
 *          },
 *          "get_brief_valide_formateur"={
 *              "method"="GET",
 *              "path"="/formateurs/{id}/briefs/valide",
 *              "controller"=BriefController::class,
 *              "route_name"="show_brief_valide_formateur",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))"
 *          },
 *          "get_promo_id_brief_id"={
 *              "method"="GET",
 *              "path"="/formateurs/promotions/{id_promo}/briefs/{id_brief}",
 *              "controller"=BriefController::class,
 *              "route_name"="show_promo_id_brief_id",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))"
 *          },
 *          "get_brief_by_promoId_apprenant"={
 *              "method"="GET",
 *              "path"="/apprenants/promotions/{id_promo}/briefs",
 *              "controller"=BriefController::class,
 *              "route_name"="show_brief_by_promoId_apprenant",
 *              "access_control"="(is_granted('ROLE_APPRENANT'))"
 *          },
 *          "get_brief_by_promo_and_formateur"={
 *              "method"="GET",
 *              "path"="/formateurs/{id_formateur}/promotions/{id_promo}/briefs/{id_brief}",
 *              "controller"=BriefController::class,
 *              "route_name"="show_brief_by_promo_and_formateur",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))"
 *          },
 *          "get_brief_by_promo_and_apprenant"={
 *              "method"="GET",
 *              "path"="/apprenants/{id_apprenant}/promotions/{id_promo}/briefs/{id_brief}",
 *              "controller"=BriefController::class,
 *              "route_name"="show_brief_by_promo_and_apprenant",
 *              "access_control"="(is_granted('ROLE_APPRENANT'))"
 *          },
 *          "post_livrables_by_apprenant_and_groupe"={
 *              "method"="POST",
 *              "path"="/apprenants/{id_apprenant}/groupes/{id_groupe}/livrables",
 *              "controller"=BriefController::class,
 *              "route_name"="add_livrables_by_apprenant_and_groupe",
 *              "access_control"="(is_granted('ROLE_APPRENANT'))"
 *          },
 *          "post_duplicate_brief="={
 *              "method"="POST",
 *              "path"="/formateurs/briefs/{id}",
 *              "controller"=BriefController::class,
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "route_name"="duplicate_brief"
 *          },
 *          "post_brief="={
 *              "method"="POST",
 *              "path"="/formateurs/briefs",
 *              "controller"=BriefController::class,
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "route_name"="add_brief"
 *          }
 *  }
 * )
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Langue::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La langue est obligatoire.")
     * @Groups({"brief:read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le titre est obligatoire.")
     * @Groups({"brief:read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description est obligatoire.")
     * @Groups({"brief:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le contexte est obligatoire.")
     * @Groups({"brief:read"})
     */
    private $contexte;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Les modalités pédagogiques sont obligatoire.")
     * @Groups({"brief:read"})
     */
    private $modalitePedagogique;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief", orphanRemoval=true, cascade={"persist"})
     * 
     */
    private $ressource;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $criterePerformance;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $modaliteEvaluation;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * 
     */
    private $image;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentiel;

    /**
     * @ORM\ManyToMany(targetEntity=NiveauEvaluation::class, inversedBy="briefs")
     */
    private $niveauCompetences;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, mappedBy="briefs", cascade={"persist"})
     */
    private $livrableAttendus;

    /**
     * @ORM\OneToMany(targetEntity=BriefPromotion::class, mappedBy="brief", cascade={"persist"})
     * 
     */
    private $briefPromotions;

    /**
     * @ORM\ManyToOne(targetEntity=EtatBriefGroupe::class, inversedBy="brief", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $etatBriefGroupe;

    /**
     * @ORM\ManyToOne(targetEntity=EtatBrief::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etatBrief;

    /**
     * @ORM\Column(type="text")
     * @Groups({"briefGroupe:read","brief:write","brief:read"})
     * @Assert\NotBlank(message="Les livrables sont obligatoire.")
     */
    private $livrables;

    public function __construct()
    {
        $this->ressource = new ArrayCollection();
        $this->niveauCompetences = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->livrableAttendus = new ArrayCollection();
        $this->briefPromotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function resetId()
    {
        $this->id = null;
    }

    public function getLangue(): ?Langue
    {
        return $this->langue;
    }

    public function setLangue(?Langue $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
    }

    public function getModalitePedagogique(): ?string
    {
        return $this->modalitePedagogique;
    }

    public function setModalitePedagogique(string $modalitePedagogique): self
    {
        $this->modalitePedagogique = $modalitePedagogique;

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessource(): Collection
    {
        return $this->ressource;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressource->contains($ressource)) {
            $this->ressource[] = $ressource;
            $ressource->setBrief($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressource->contains($ressource)) {
            $this->ressource->removeElement($ressource);
            // set the owning side to null (unless already changed)
            if ($ressource->getBrief() === $this) {
                $ressource->setBrief(null);
            }
        }

        return $this;
    }

    public function getCriterePerformance(): ?string
    {
        return $this->criterePerformance;
    }

    public function setCriterePerformance(?string $criterePerformance): self
    {
        $this->criterePerformance = $criterePerformance;

        return $this;
    }

    public function getModaliteEvaluation(): ?string
    {
        return $this->modaliteEvaluation;
    }

    public function setModaliteEvaluation(?string $modaliteEvaluation): self
    {
        $this->modaliteEvaluation = $modaliteEvaluation;

        return $this;
    }

    public function getImage()
    {
       // return $this->image;
        return $this->image!=null?stream_get_contents($this->image):null;
    }

    public function setImage($image): self
    {
       // $this->image = $image;
        $this->image = base64_encode($image);

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

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
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    /**
     * @return Collection|LivrableAttendu[]
     */
    public function getLivrableAttendus(): Collection
    {
        return $this->livrableAttendus;
    }

    public function addLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if (!$this->livrableAttendus->contains($livrableAttendu)) {
            $this->livrableAttendus[] = $livrableAttendu;
            $livrableAttendu->addBrief($this);
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if ($this->livrableAttendus->contains($livrableAttendu)) {
            $this->livrableAttendus->removeElement($livrableAttendu);
            $livrableAttendu->removeBrief($this);
        }

        return $this;
    }

    /**
     * @return Collection|BriefPromotion[]
     */
    public function getBriefPromotions(): Collection
    {
        return $this->briefPromotions;
    }

    public function addBriefPromotion(BriefPromotion $briefPromotion): self
    {
        if (!$this->briefPromotions->contains($briefPromotion)) {
            $this->briefPromotions[] = $briefPromotion;
            $briefPromotion->setBrief($this);
        }

        return $this;
    }

    public function removeBriefPromotion(BriefPromotion $briefPromotion): self
    {
        if ($this->briefPromotions->contains($briefPromotion)) {
            $this->briefPromotions->removeElement($briefPromotion);
            // set the owning side to null (unless already changed)
            if ($briefPromotion->getBrief() === $this) {
                $briefPromotion->setBrief(null);
            }
        }

        return $this;
    }

    public function getEtatBriefGroupe(): ?EtatBriefGroupe
    {
        return $this->etatBriefGroupe;
    }

    public function setEtatBriefGroupe(?EtatBriefGroupe $etatBriefGroupe): self
    {
        $this->etatBriefGroupe = $etatBriefGroupe;

        return $this;
    }

    public function getEtatBrief(): ?EtatBrief
    {
        return $this->etatBrief;
    }

    public function setEtatBrief(?EtatBrief $etatBrief): self
    {
        $this->etatBrief = $etatBrief;

        return $this;
    }

    public function getLivrables(): ?string
    {
        return $this->livrables;
    }

    public function setLivrables(string $livrables): self
    {
        $this->livrables = $livrables;

        return $this;
    }
}
