<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrableApprenantRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivrableApprenantRepository::class)
 * @ApiResource()
 */
class LivrableApprenant
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
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=LivrableAttendu::class, inversedBy="livrableApprenants", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $livrableAttendu;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="livrableApprenants")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"brief_livrable_partiel:read"})
     */
    private $apprenant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function resetId()
    {
        $this->id = null;
    }
    
    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getLivrableAttendu(): ?LivrableAttendu
    {
        return $this->livrableAttendu;
    }

    public function setLivrableAttendu(?LivrableAttendu $livrableAttendu): self
    {
        $this->livrableAttendu = $livrableAttendu;

        return $this;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }
}
