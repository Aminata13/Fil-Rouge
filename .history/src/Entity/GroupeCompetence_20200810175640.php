<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
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
 *  routePrefix="/admin",
 *  normalizationContext={"groups"={"grpcompetence:read"}},
 *  collectionOperations={
 *      "get"={
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"grpcompetence:read_all"}}
 *      },
 *      "getByCompetences"={
 *          "method"="GET",
 *          "path"="/groupe_competences/competences",
 *          "access_control"="(is_granted('ROLE_ADMIN'))"
 *      },
 *      "post_groupe_competence"={
 *         "method"="POST",
 *         "path"="/groupe_competences",
 *         "controller"=AddGroupeCompetence::class,
 *         "access_control"="(is_granted('ROLE_ADMIN'))",
 *         "route_name"="add_groupe_competence",
 *         "denormalization_context"={"groups"={"grpcompetence:write"}}
 *     }
 *  },
 *  itemOperations={
 *      "get"={
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"grpcompetence:read_all"}}
 *      },
 *      "getByIdCompetence"={
 *          "method"="GET",
 *          "path"="/groupe_competences/{id}/competences",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *      },
 *      "put_groupe_competence"={
 *         "method"="PUT",
 *         "path"="/groupe_competences/{id}",
 *         "controller"=EditGroupeCompetenceController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *         "route_name"="edit_groupe_competence",
 *         "denormalization_context"={"groups"={"grpcompetence:write"}}
 *     }
 *  }
 * )
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 */
class GroupeCompetence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"grpcompetence:read","grpcompetence:read_all","referentiel:read","referentiel:read_all","promotion:read_all_ref"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpcompetence:read","grpcompetence:read_all","referentiel:read","referentiel:read_all","grpcompetence:write","competence:write","promotion:read_all_ref"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"grpcompetence:read","grpcompetence:read_all","referentiel:read","referentiel:read_all","grpcompetence:write","promotion:read_all_ref"})
     * @Assert\NotBlank(message="Une description est requise.")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences", cascade={"persist"})
     * @Groups({"grpcompetence:read","grpcompetence:read_all","referentiel:read_all","grpcompetence:write","promotion:read_all_ref"})
     */
    private $competences;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="groupeCompetences")
     */
    private $referentiels;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }



    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
        }

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
            $referentiel->addGroupeCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->contains($referentiel)) {
            $this->referentiels->removeElement($referentiel);
            $referentiel->removeGroupeCompetence($this);
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
}
