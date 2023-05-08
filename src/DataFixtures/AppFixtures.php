<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserService $userService;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserService $userService
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->userService = $userService;
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser->setNick('admin');
        $adminUser->setFirstName('Admin');
        $adminUser->setLastName('Admin');
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser,'admin'));
        $adminUser->setRoles('ROLE_ADMIN');

        $manager->persist($adminUser);

        $users = [];
        for($i = 1;$i <= 100;$i++) {
            $user = new User();

            $user->setNick("user-{$i}");
            $user->setFirstName("firstname-{$i}");
            $user->setLastName("lastname-{$i}");
            $user->setPassword($this->passwordHasher->hashPassword($adminUser,'user'));
            $user->setRoles('ROLE_USER');

            $manager->persist($user);

            $users[] = $user;
        }

        // Randomize followers
        foreach($users as $user) {
            $followingCount = rand(1,10);
            $followers = [];

            for($i = 0;$i < $followingCount;$i++) {
                $randomIndex = rand(0, 99);

                $follower= $users[$randomIndex];

                if(!in_array($follower, $followers, true)) {
                    if($user !== $follower) {
                        $this->userService->followUser($user, $follower);
                        $followers[] = $follower;
                    }
                }
            }
        }

        $manager->flush();
    }
}
