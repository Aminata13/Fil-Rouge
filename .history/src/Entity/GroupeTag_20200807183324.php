<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

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
 *          "normalization_context"={"groups"={"tag:read"}}
 *      },
 *      "post"
 *  },
 *  itemOperations={
 *      "put",
 *      "get"={
 *          "normalization_context"={"groups"={"tag:read"}}
 *      },
 *      "getByTag"={
 *          "method"="GET",
 *          "path"="/groupe_tags/{id}/tags"
 *      }
 *  }
 * )
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 */
class GroupeTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"tag:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tag:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags")
     * @Groups({"tag:read"})
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
}
