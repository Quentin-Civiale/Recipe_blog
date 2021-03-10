<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(unique=true)
     */
    private $email = null;

    /**
     * @var string|null
     * @ORM\Column()
     */
    private $password = null;

    /**
     * @var string|null
     * @ORM\Column(unique=true)
     */
    private $pseudo = null;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_password;

    /**
     * @ORM\ManyToMany(targetEntity=Recipe::class, mappedBy="favorite")
     */
    private $favorite;

    /**
     * User constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->registeredAt = new \DateTimeImmutable();
        $this->favorite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @param string|null $pseudo
     */
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getRegisteredAt(): \DateTimeImmutable
    {
        return $this->registeredAt;
    }

    /**
     * @param DateTimeImmutable $registeredAt
     */
    public function setRegisteredAt(\DateTimeImmutable $registeredAt): void
    {
        $this->registeredAt = $registeredAt;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return['ROLE_USER'];
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    public function getResetPassword(): ?string
    {
        return $this->reset_password;
    }

    public function setResetPassword(?string $reset_password): self
    {
        $this->reset_password = $reset_password;

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(Recipe $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
            $favorite->addFavorite($this);
        }

        return $this;
    }

    public function removeFavorite(Recipe $favorite): self
    {
        if ($this->favorite->removeElement($favorite)) {
            $favorite->removeFavorite($this);
        }

        return $this;
    }
}
