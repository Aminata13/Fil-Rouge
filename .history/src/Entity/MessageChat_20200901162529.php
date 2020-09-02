<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageChatRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MessageChatRepository::class)
 *  @UniqueEntity(
 *  fields={"libelle"},
 *  message="Le libelle existe déjà."
 * )
 * @ApiResource(
 *  collectionOperations={
 *       "get_messages_apprenant"={
 *          "method"="GET",
 *          "path"="/users/promotions/{id_promo}/apprenants/{id_apprenant}/chats",
 *          "controller"=ChatController::class,
 *          "route_name"="show_messages_apprenant"
 *      },
 *      "post_messages_apprenant"={
 *          "method"="POST",
 *          "path"="/users/promotions/{id_promo}/apprenants/{id_apprenant}/chats",
 *          "controller"=ChatController::class,
 *          "route_name"="add_messages_apprenant"
 *      },
 *      "get_commentaire_users"={
 *         "method"="GET",
 *         "path"="/users/promotions/id/chats",
 *         "controller"=ChatController::class,
 *         "access_control"="(is_granted('ROLE_USER') or is_granted('ROLE_ADMIN'))",
 *         "route_name"="get_commentaire_by_users"
 *     }
 *  },
 *  itemOperations={
 *      "put","get"
 * })
 */
class MessageChat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"chat:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"chat:read","commentaire:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"chat:read"})
     */
    private $date;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"chat:read"})
     */
    private $pieceJointe;

    /**
     * @ORM\ManyToOne(targetEntity=FilDiscussion::class, inversedBy="messageChats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filDiscussion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messageChats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPieceJointe()
    {
        return $this->pieceJointe!=null?stream_get_contents($this->pieceJointe):null;
    }

    public function setPieceJointe($pieceJointe): self
    {
        $this->pieceJointe = base64_encode($pieceJointe);

        return $this;
    }

    public function getFilDiscussion(): ?FilDiscussion
    {
        return $this->filDiscussion;
    }

    public function setFilDiscussion(?FilDiscussion $filDiscussion): self
    {
        $this->filDiscussion = $filDiscussion;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
