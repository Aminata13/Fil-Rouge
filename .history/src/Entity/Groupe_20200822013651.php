<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *  attributes={
 *      "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *      "security_message"="Vous n'avez pas accès à cette ressource."
 *  },
 *  routePrefix="/admin",
 *  collectionOperations={
 *      "get"={
 *          "normalization_context"={"groups"={"groupe:read"}}
 *      },
 *      "get_apprenants_groupe"={
 *          "method"="GET",
 *          "path"="/groupes/apprenants",
 *          "controller"=ShowApprenantsByGroupe::class,
 *          "route_name"="show_apprenants_groupe",
 *          "normalization_context"={"groups"={"apprenants_groupe:read"}}
 *      },
 *      "post_groupe"={
 *         "method"="POST",
 *         "path"="/groupes",
 *         "controller"=AddGroupeController::class,
 *         "route_name"="add_groupe",
 *         "denormalization_context"={"groups"={"groupe:write"}}
 *     }
 *  },
 *  itemOperations={
 *      "get"={
 *          "normalization_context"={"groups"={"groupe:read"}}
 *      },
 *      "put_groupe"={
 *         "method"="PUT",
 *         "path"="/groupes/{id}",
 *         "controller"=AddGroupeController::class,
 *         "route_name"="edit_groupe",
 *         "denormalization_context"={"groups"={"groupe:write"}}
 *      },
 *      "delete_apprenant"={
 *         "method"="DELETE",
 *         "path"="/groupes/{id_groupe}/apprenants/{id_apprenant}",
 *         "controller"=AddGroupeController::class,
 *         "route_name"="delete_apprenant_groupe",
 *         "denormalization_context"={"groups"={"groupe:write"}}
 *     }
 *  }
 * )
 */
class Groupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"briefGroupe:read","groupe:read","apprenants_groupe:read","promotion:read","promotion:read_all","promo_groupe_apprenants:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"briefGroupe:read","groupe:read","apprenants_groupe:read","promotion:read","promotion:read_all","promo_groupe_apprenants:read"})
     * @Assert\NotBlank(message="Le libelle est obligatoire.")
     */
    private $libelle;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="La date de creation du groupe est obligatoire.")
     * @Groups({"groupe:read","apprenants_groupe:read","promotion:read","promotion:read_all","promo_groupe_apprenants:read"})
     */
    private $dateCreation;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * @Groups({"groupe:read","promotion:read_all","apprenants_groupe:read","promo_groupe_apprenants:read"})
     */
    private $apprenants;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="groupes")
     * @Groups({"groupe:read"})
     */
    private $promotion;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, mappedBy="groupes")
     * @Groups({"groupe:read"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToOne(targetEntity=EtatBriefGroupe::class, inversedBy="groupe")
     */
    private $etatBriefGroupe;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

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
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
        }

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
            $formateur->addGroupe($this);
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
            $formateur->removeGroupe($this);
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
}
