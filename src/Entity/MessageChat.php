<?php

namespace App\Entity;

use App\Repository\MessageChatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageChatRepository::class)
 */
class MessageChat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="blob")
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
        return $this->pieceJointe;
    }

    public function setPieceJointe($pieceJointe): self
    {
        $this->pieceJointe = $pieceJointe;

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
