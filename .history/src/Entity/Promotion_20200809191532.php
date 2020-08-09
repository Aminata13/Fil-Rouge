<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 * @ApiResource(
 *  routePrefix="/admin",
 *  normalizationContext={"groups"={"promotion:read"}},
 *  attributes={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')"},
 *  collectionOperations={
 *      "get"={
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *      },
 *     "get_promo_principal"={
 *          "method"="GET",
 *          "path"="/promotion/principal",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"promotion:read_all"}}
 *      },
 *      "get_apprenants_attente"={
 *         "method"="GET",
 *         "path"="/promotion/apprenants/attente",
 *         "controller"=ShowApprenantsAttente::class,
 *         "route_name"="show_apprenants_attente"
 *     }
 *  },
 *  itemOperations={
 *      "get"={
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *      }, 
 *      "get_promo_principal_id"={
 *          "method"="GET",
 *          "path"="/promotion/{id}/principal",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"promotion:read_all"}}
 *      },  
 *      "get_promo_id_referentiel"={
 *          "method"="GET",
 *          "path"="/promotion/{id}/referentiels",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"promotion:read_all_ref"}}
 *      },    
 *      "get_apprenants_id_attente"={
 *         "method"="GET",
 *         "path"="/promotion/{id}/apprenants/attente",
 *         "controller"=ShowApprenantsAttenteById::class,
 *         "route_name"="show_apprenants_id_attente",
 *         "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *     },
 *      "get_promo_id_groupes_id_apprenants"={
 *          "method"="GET",
 *          "path"="/promotion/{id_promo}/groupes/{id_groupe}/apprenants",
 *          "requirements"={"id_promo"="\d+"},
 *          "controller"=ShowApprenantsByGrouId::class,
 *          "route_name"="showpromo_id_groupes_id_apprenants",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      }
 *  }
 * )
 */
class Promotion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $dateFin;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="promotions")
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $referentiels;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Langue::class, inversedBy="promotions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $langue;

    /**
     * @ORM\ManyToOne(targetEntity=Fabrique::class, inversedBy="promotions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    private $fabrique;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promotion")
     * @Groups({"promotion:read","promotion:read_all"})
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promotion")
     * @Groups({"promotion:read_all"})
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, mappedBy="promotions")
     * @Groups({"promotion:read","promotion:read_all"})
     */
    private $formateurs;

    public function __construct()
    {
        $this->referentiels = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(?string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->contains($referentiel)) {
            $this->referentiels->removeElement($referentiel);
        }

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

    public function getLangue(): ?Langue
    {
        return $this->langue;
    }

    public function setLangue(?Langue $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getFabrique(): ?Fabrique
    {
        return $this->fabrique;
    }

    public function setFabrique(?Fabrique $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromotion($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getPromotion() === $this) {
                $groupe->setPromotion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setPromotion($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            // set the owning side to null (unless already changed)
            if ($apprenant->getPromotion() === $this) {
                $apprenant->setPromotion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
            $formateur->addPromotion($this);
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
            $formateur->removePromotion($this);
        }

        return $this;
    }
}
