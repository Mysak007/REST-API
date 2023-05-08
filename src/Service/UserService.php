<?php

namespace App\Service;

use App\Entity\Follower;
use App\Entity\User;
use App\Exception\AlreadyFollowingException;
use App\Exception\AlreadyRegisteredException;
use App\Repository\FollowerRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserRepository $userRepository;
    private FollowerRepository $followerRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserRepository $userRepository,
        FollowerRepository $followerRepository,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->userRepository = $userRepository;
        $this->followerRepository = $followerRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function createUser(User $user): void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));

        try{
            $this->userRepository->add($user, true);
        }catch(UniqueConstraintViolationException  $e){
            throw new AlreadyRegisteredException($user->getNick());
        }
    }

    public function followUser(User $follow, User $follower): void
    {
        $new = new Follower();

        $new->setFollow($follow);
        $new->setFollower($follower);

        try{
            $this->followerRepository->add($new, true);
        }catch(UniqueConstraintViolationException  $e){
            throw new AlreadyFollowingException($follower->getNick(), $follow->getNick());
        }
    }
}
