<?php

namespace App\Entity;

use App\Repository\FilDiscussionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FilDiscussionRepository::class)
 * @ApiResource()
 */
class FilDiscussion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity=Promotion::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $promotion;

    /**
     * @ORM\OneToMany(targetEntity=MessageChat::class, mappedBy="filDiscussion", orphanRemoval=true,cascade={"persist"})
     */
    private $messageChats;

    public function __construct()
    {
        $this->messageChats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * @return Collection|MessageChat[]
     */
    public function getMessageChats(): Collection
    {
        return $this->messageChats;
    }

    public function addMessageChat(MessageChat $messageChat): self
    {
        if (!$this->messageChats->contains($messageChat)) {
            $this->messageChats[] = $messageChat;
            $messageChat->setFilDiscussion($this);
        }

        return $this;
    }

    public function removeMessageChat(MessageChat $messageChat): self
    {
        if ($this->messageChats->contains($messageChat)) {
            $this->messageChats->removeElement($messageChat);
            // set the owning side to null (unless already changed)
            if ($messageChat->getFilDiscussion() === $this) {
                $messageChat->setFilDiscussion(null);
            }
        }

        return $this;
    }
}
