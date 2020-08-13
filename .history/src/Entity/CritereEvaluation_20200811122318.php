<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CritereEvaluationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  itemOperations={
 *      "put","get"
 *  }
 * )
 * @ORM\Entity(repositoryClass=CritereEvaluationRepository::class)
 */
class CritereEvaluation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promo_groupe_apprenants:read","referentiel:read","referentiel:read_all","promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_groupe_apprenants:read","referentiel:read","referentiel:read_all","promotion:read","promotion:read_all","promotion:read_all_ref"})
     */
    protected $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="critereEvaluations")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $referentiel;

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

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }
}
