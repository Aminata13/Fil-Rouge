<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RessourceRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RessourceRepository::class)
 * @ApiResource()
 */
class Ressource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief_livrable_partiel:read","brief:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"brief_livrable_partiel:read","brief:read"})
     */
    private $url;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"brief_livrable_partiel:read","brief:read"})
     */
    private $pieceJointe;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="ressource")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brief;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPieceJointe()
    {
        return $this->pieceJointe;
        return $this->pieceJointe!=null?stream_get_contents($this->image):null;
    }

    public function setPieceJointe($pieceJointe): self
    {
       // $this->pieceJointe = $pieceJointe;
        $this->pieceJointe = base64_encode($pieceJointe);

        return $this;
    }

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }
}
