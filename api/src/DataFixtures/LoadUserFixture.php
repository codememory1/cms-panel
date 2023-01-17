<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class LoadUserFixture extends Fixture
{
    private array $users = [
        [
            'name' => 'Developer',
            'email' => 'developer@gmail.com',
            'password' => 'developer',
            'status' => UserStatusEnum::ACTIVATED
        ],
        [
            'name' => 'Blocked',
            'email' => 'blocked@gmail.com',
            'password' => 'blocked',
            'status' => UserStatusEnum::BLOCKED
        ],
        [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'test',
            'status' => UserStatusEnum::ACTIVATED
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->users as $userData) {
            $user = new User();

            $user->setName($userData['name']);
            $user->setEmail($userData['email']);
            $user->setPassword($userData['password']);
            $user->setStatus($userData['status']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}