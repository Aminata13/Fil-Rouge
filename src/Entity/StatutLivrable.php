<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StatutLivrableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StatutLivrableRepository::class)
 * @ApiResource()
 */
class StatutLivrable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=LivrableRendu::class, mappedBy="statut")
     */
    private $livrableRendus;

    public function __construct()
    {
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
            $livrableRendu->setStatut($this);
        }

        return $this;
    }

    public function removeLivrableRendu(LivrableRendu $livrableRendu): self
    {
        if ($this->livrableRendus->contains($livrableRendu)) {
            $this->livrableRendus->removeElement($livrableRendu);
            // set the owning side to null (unless already changed)
            if ($livrableRendu->getStatut() === $this) {
                $livrableRendu->setStatut(null);
            }
        }

        return $this;
    }
}
