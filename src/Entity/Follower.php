<?php

namespace App\Entity;

use App\Repository\FollowerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups as JMSGroups;

#[ORM\Entity(repositoryClass: FollowerRepository::class)]
#[ORM\Table()]
#[ORM\UniqueConstraint(name: "unique_follower", fields: ['follow','follower'])]
class Follower
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[JMSGroups(["follow_list"])]
    private \DateTimeImmutable $created;

    #[ORM\ManyToOne(inversedBy: 'followers')]
    #[ORM\JoinColumn()]
    private User $follow;

    #[ORM\ManyToOne(inversedBy: 'followedBy')]
    #[ORM\JoinColumn()]
    #[JMSGroups(["follow_list"])]
    private User $follower;

    public function __construct()
    {
        $this->created = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function getFollow(): User
    {
        return $this->follow;
    }

    public function setFollow(User $follow): self
    {
        $this->follow = $follow;

        return $this;
    }

    public function getFollower(): User
    {
        return $this->follower;
    }

    public function setFollower(User $follower): self
    {
        $this->follower = $follower;

        return $this;
    }
}
