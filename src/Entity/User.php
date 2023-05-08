<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTimeImmutable;
use JMS\Serializer\Annotation\Groups as JMSGroups;
use JMS\Serializer\Annotation\VirtualProperty;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[JMSGroups(["user_detail", "user_all", "follow_list"])]
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private string $id;

    #[JMSGroups(["user_detail", "user_all", "follow_list"])]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[JMSGroups(["user_detail", "user_all", "follow_list", "create_user"])]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $firstName;

    #[JMSGroups(["user_detail", "user_all", "follow_list", "create_user"])]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $lastName;

    #[JMSGroups(["user_detail", "user_all", "follow_list", "create_user"])]
    #[ORM\Column(type: Types::STRING, length: 100, unique: true)]
    private string $nick;

    #[JMSGroups(["create_user"])]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $password;

    #[JMSGroups(["create_user"])]
    #[ORM\Column(type: Types::JSON)]
    #[Type('array')]
    private array $roles;

    #[ORM\OneToMany(mappedBy: 'follow', targetEntity: Follower::class, cascade: ["all"])]
    private Collection $followers;

    #[ORM\OneToMany(mappedBy: 'follower', targetEntity: Follower::class, cascade: ["all"])]
    private Collection $followedBy;

    public function __construct()
    {
        $this->followers = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    #[VirtualProperty]
    #[JMSGroups(["user_detail", "user_all", "follow_list"])]
    public function countFollowers(): int
    {
        return $this->getFollowers()->count();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getNick(): ?string
    {
        return $this->nick;
    }

    public function setNick($nick): self
    {
        $this->nick = $nick;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(string $role): void
    {
      $this->roles[] = $role;
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->nick;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Collection<int, Follower>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(Follower $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers[] = $follower;
            $follower->setFollow($this);
        }

        return $this;
    }

    public function removeFollower(Follower $follower): self
    {
        if ($this->followers->removeElement($follower)) {
            // set the owning side to null (unless already changed)
            if ($follower->getFollow() === $this) {
                $follower->setFollow(null);
            }
        }

        return $this;
    }

}
