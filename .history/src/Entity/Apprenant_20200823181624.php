<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 * subresourceOperations={
 *      "apprenants_get_subresource"={
 *        "normalization_context"={"groups"={"profil_sortie_promo:read"}},
 *        "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *      }
 *  },
 *  itemOperations={
 *      "put","get"
 *  },
 *  normalizationContext={"groups"={"apprenant:read"}}
 * )
 */
class Apprenant 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"apprenant_competence:read","brief_livrable_partiel:read","briefGroupe:read","profil_sortie_promo:read","groupe:read","apprenants_groupe:read","apprenant:read","promotion:read_all","promo_groupe_apprenants:read"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"apprenant_competence:read""brief_livrable_partiel:read","briefGroupe:read","profil_sortie_promo:read","groupe:read","apprenants_groupe:read","apprenant:read","promotion:read_all","promo_groupe_apprenants:read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     * @Groups({"profil_sortie_promo:read","groupe:read","apprenants_groupe:read","apprenant:read","promotion:read_all","promo_groupe_apprenants:read"})
     */
    private $profilSortie;

    /**
     * @ORM\ManyToOne(targetEntity=Statut::class, inversedBy="apprenants")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"groupe:read","apprenants_groupe:read","apprenant:read","promotion:read_all","promo_groupe_apprenants:read"})
     */
    private $statut;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenants")
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="apprenants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $promotion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $attente=true;

    /**
     * @ORM\OneToMany(targetEntity=LivrableApprenant::class, mappedBy="apprenant", orphanRemoval=true)
     */
    private $livrableApprenants;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValide::class, mappedBy="apprenant", orphanRemoval=true)
     */
    private $competenceValides;

    /**
     * @ORM\OneToMany(targetEntity=LivrableRendu::class, mappedBy="apprenant", orphanRemoval=true)
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $livrableRendus;

    /**
     * @ORM\OneToMany(targetEntity=BriefApprenant::class, mappedBy="apprenant", orphanRemoval=true)
     */
    private $briefApprenants;

   

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->livrableApprenants = new ArrayCollection();
        $this->competenceValides = new ArrayCollection();
        $this->livrableRendus = new ArrayCollection();
        $this->briefApprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

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
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            $groupe->removeApprenant($this);
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

    public function getAttente(): ?bool
    {
        return $this->attente;
    }

    public function setAttente(bool $attente): self
    {
        $this->attente = $attente;

        return $this;
    }

    /**
     * @return Collection|LivrableApprenant[]
     */
    public function getLivrableApprenants(): Collection
    {
        return $this->livrableApprenants;
    }

    public function addLivrableApprenant(LivrableApprenant $livrableApprenant): self
    {
        if (!$this->livrableApprenants->contains($livrableApprenant)) {
            $this->livrableApprenants[] = $livrableApprenant;
            $livrableApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removeLivrableApprenant(LivrableApprenant $livrableApprenant): self
    {
        if ($this->livrableApprenants->contains($livrableApprenant)) {
            $this->livrableApprenants->removeElement($livrableApprenant);
            // set the owning side to null (unless already changed)
            if ($livrableApprenant->getApprenant() === $this) {
                $livrableApprenant->setApprenant(null);
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
            $competenceValide->setApprenant($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValide $competenceValide): self
    {
        if ($this->competenceValides->contains($competenceValide)) {
            $this->competenceValides->removeElement($competenceValide);
            // set the owning side to null (unless already changed)
            if ($competenceValide->getApprenant() === $this) {
                $competenceValide->setApprenant(null);
            }
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
            $livrableRendu->setApprenant($this);
        }

        return $this;
    }

    public function removeLivrableRendu(LivrableRendu $livrableRendu): self
    {
        if ($this->livrableRendus->contains($livrableRendu)) {
            $this->livrableRendus->removeElement($livrableRendu);
            // set the owning side to null (unless already changed)
            if ($livrableRendu->getApprenant() === $this) {
                $livrableRendu->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefApprenant[]
     */
    public function getBriefApprenants(): Collection
    {
        return $this->briefApprenants;
    }

    public function addBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if (!$this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants[] = $briefApprenant;
            $briefApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removeBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if ($this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants->removeElement($briefApprenant);
            // set the owning side to null (unless already changed)
            if ($briefApprenant->getApprenant() === $this) {
                $briefApprenant->setApprenant(null);
            }
        }

        return $this;
    }

   


   
}
