<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 * @ApiResource(
 *      routePrefix="/formateurs",
 *      collectionOperations={
 *          "get"={
 *              ""
 *              "normalization_context"={"groups"={"brief:read"}},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *          },
 *          "get_brief_by_promoId_by_groupe_id"={
    *          "method"="GET",
    *          "path"="/promotions/{id_promo}/groupe/{id_groupe}/briefs",
    *          "controller"=BriefController::class,
    *          "route_name"="show_brief_by_promoId_by_groupe_id",
    *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"

 *          },
 *          "get_brief_by_promoId"={
    *          "method"="GET",
    *          "path"="/promotions/{id_promo}/briefs",
    *          "controller"=BriefController::class,
    *          "route_name"="show_brief_by_promoId"
 *          },
 *          "get_brief_brouillons_formateur"={
    *          "method"="GET",
    *          "path"="/{id}/briefs/brouillons",
    *          "controller"=BriefController::class,
    *          "route_name"="show_brief_brouillons_formateur"
 *          }
 * }
 * )
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Langue::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $contexte;

    /**
     * @ORM\Column(type="text")
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $modalitePedagogique;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief", orphanRemoval=true)
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $ressource;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $criterePerformance;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $modaliteEvaluation;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $image;

    /**
     * @ORM\Column(type="date")
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"briefGroupe:read"})
     */
    private $referentiel;

    /**
     * @ORM\ManyToMany(targetEntity=NiveauEvaluation::class, inversedBy="briefs")
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $niveauCompetences;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     * @Groups({"briefGroupe:read","brief:read"})
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"briefGroupe:read"})
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, mappedBy="briefs")
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $livrableAttendus;

    /**
     * @ORM\OneToMany(targetEntity=BriefPromotion::class, mappedBy="brief")
     * 
     */
    private $briefPromotions;

    /**
     * @ORM\ManyToOne(targetEntity=EtatBriefGroupe::class, inversedBy="brief")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etatBriefGroupe;

    /**
     * @ORM\ManyToOne(targetEntity=EtatBrief::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"brief:read","briefGroupe:read"})
     */
    private $etatBrief;

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
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

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
        return $this->niveauCompetence;
    }

    public function addNiveauCompetence(NiveauEvaluation $niveauCompetence): self
    {
        if (!$this->niveauCompetence->contains($niveauCompetence)) {
            $this->niveauCompetence[] = $niveauCompetence;
        }

        return $this;
    }

    public function removeNiveauCompetence(NiveauEvaluation $niveauCompetence): self
    {
        if ($this->niveauCompetence->contains($niveauCompetence)) {
            $this->niveauCompetence->removeElement($niveauCompetence);
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
}
