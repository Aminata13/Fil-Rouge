<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\ApprenantController;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(iri="http://schema.org/Users",
 *  collectionOperations={
 *      "get"={
 *          "path"="/admin/users",
 *          "access_control"="(is_granted('ROLE_ADMIN'))"
 *      },
 *      "show_apprenants"={
 *         "method"="GET",
 *         "path"="/eleves",
 *         "controller"=ApprenantController::class,
 *         "access_control"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *         "route_name"="apprenant_liste"
 *     },
 *      "show_formateurs"={
 *         "method"="GET",
 *         "path"="/formateurs",
 *         "controller"=FormateurController::class,
 *         "access_control"="(is_granted('ROLE_CM'))",
 *         "route_name"="formateur_liste"
 *     },
 *      "add_user" = {
 *          "method"="POST",
 *          "path"="/admin/users",
 *          "route_name"="add_user"
 *      }
 *  },
 *  subresourceOperations={
 *      "api_user_profils_users_get_subresource"={
 *          "access_control"="(is_granted('ROLE_ADMIN'))"
 *      }
 *  },
 *  itemOperations={
 *      "get"={
 *          "path"="/admin/users/{id}",
 *          "access_control"="(is_granted('ROLE_ADMIN'))"
 *      }, 
 *      "show_formateur"={
 *          "method"="GET",
 *          "path"="/formateurs/{id}",
 *          "security"="is_granted('FORMATEUR_VIEW', object)",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *      "show_apprenant"={
 *          "method"="GET",
 *          "path"="/apprenants/{id}",
 *          "security"="is_granted('APPRENANT_VIEW', object)",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *      "put"={
 *          "path"="/admin/users/{id}",
 *          "access_control"="(is_granted('ROLE_ADMIN'))"
 *      },
 *      "edit_formateur"={
 *          "method"="PUT",
 *          "path"="/formateurs/{id}",
 *          "security"="is_granted('FORMATEUR_EDIT', object)",
 *          "security_message"="Vous n'avez pas le droit de modifier ces informations."
 *      },
 *      "edit_apprenant"={
 *          "method"="PUT",
 *          "path"="/apprenants/{id}",
 *          "security"="is_granted('APPRENANT_EDIT', object)",
 *          "security_message"="Vous n'avez pas le droit de modifier ces informations."
 *      }
 *      "show_user_connecte"={
 *         "method"="GET",
 *         "path"="/user",
 *         "controller"=UserController::class,
 *         "route_name"="formateur_liste"
 *     },
 * }
 * )
 * @UniqueEntity(
 * fields={"username"},
 * message="Le username doit être unique.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"apprenant_competence:read","brief_livrable_partiel:read","briefGroupe:read","briefGroupe:read","profil_sortie_promo:read","groupe:read","apprenant:read","promotion:read","promotion:read_all","promo_groupe_apprenants:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Le nom d'utilisateur est obligatoire.")
     */
    private $username;

    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le mot de passe est obligatoire.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prénom est obligatoire.")
     * @Groups({"apprenant_competence:read","brief_livrable_partiel:read","briefGroupe:read","profil_sortie_promo:read","groupe:read","apprenants_groupe:read","apprenant:read","promotion:read","promotion:read_all","promo_groupe_apprenants:read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     * @Groups({"apprenant_competence:read","brief_livrable_partiel:read","briefGroupe:read","briefGroupe:read","profil_sortie_promo:read","groupe:read","apprenants_groupe:read","apprenant:read","promotion:read","promotion:read_all","promo_groupe_apprenants:read"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse mail est obligatoire.")
     * @Assert\Email(message="L'adresse mail est invalide")
     * @Groups({"apprenant_competence:read","brief_livrable_partiel:read","briefGroupe:read","profil_sortie_promo:read","apprenant:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Assert\NotBlank(message="L'avatar est obligatoire.")
     * @Groups({"apprenant_competence:read","brief_livrable_partiel:read","briefGroupe:read"})
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity=UserProfil::class, inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le profil est obligatoire.")
     */
    private $profil;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted=false;

    /**
     * @ORM\OneToMany(targetEntity=MessageChat::class, mappedBy="user", orphanRemoval=true)
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.strtoupper($this->profil->getLibelle());

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar(): ?string
    {
        
        return $this->avatar!=null?stream_get_contents($this->avatar):null;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = base64_encode($avatar);

        return $this;
    }

    public function getProfil(): ?UserProfil
    {
        return $this->profil;
    }

    public function setProfil(?UserProfil $profil): self
    {
        $this->profil = $profil;

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

    public function sendEmail(\Swift_Mailer $mailer,$password){
        $msg = (new \Swift_Message('Sonatel Academy'))
        ->setFrom('dioufbadaraalioune7@gmail.com')
        ->setTo($this->email)
        ->setBody("Bonjour votre password est : " . $password . " Et votre username " . $this->username);
        $mailer->send($msg);
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
            $messageChat->setUser($this);
        }

        return $this;
    }

    public function removeMessageChat(MessageChat $messageChat): self
    {
        if ($this->messageChats->contains($messageChat)) {
            $this->messageChats->removeElement($messageChat);
            // set the owning side to null (unless already changed)
            if ($messageChat->getUser() === $this) {
                $messageChat->setUser(null);
            }
        }

        return $this;
    }
}
