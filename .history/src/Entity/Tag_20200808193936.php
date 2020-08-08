<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le libelle existe déjà."
 * )
 * @ApiResource(
 *  attributes={
 *      "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *      "security_message"="Vous n'avez pas accès à cette ressource."
 *  },
 *  routePrefix="/admin",
 *  collectionOperations={
 *      "get"={
 
 *          "normalization_context"={"groups"={"tag:read_all"}}
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
 *      "put","get"
 *  }
 *  
 * )
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"tag:read","groupe_tag:read_tag"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tag:read","groupe_tag:read_tag"})
     * @Assert\NotBlank(message="le libelle d'un tag est requis.")
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tags")
     */
    private $groupeTags;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
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
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->contains($groupeTag)) {
            $this->groupeTags->removeElement($groupeTag);
            $groupeTag->removeTag($this);
        }

        return $this;
    }
}
