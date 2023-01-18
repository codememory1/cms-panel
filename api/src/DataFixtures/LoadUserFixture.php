<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use App\Enum\UserStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class LoadUserFixture extends Fixture implements DependentFixtureInterface
{
    private array $users = [
        [
            'name' => 'Developer',
            'email' => 'developer@gmail.com',
            'password' => 'developer',
            'status' => UserStatusEnum::ACTIVATED,
            'role' => 'DEVELOPER'
        ],
        [
            'name' => 'Blocked',
            'email' => 'blocked@gmail.com',
            'password' => 'blocked',
            'status' => UserStatusEnum::BLOCKED,
            'role' => 'DEVELOPER'
        ],
        [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'test',
            'status' => UserStatusEnum::ACTIVATED,
            'role' => 'DEVELOPER'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->users as $userData) {
            /** @var Role $role */
            $role = $this->getReference("r-{$userData['role']}");
            $user = new User();

            $user->setName($userData['name']);
            $user->setEmail($userData['email']);
            $user->setPassword($userData['password']);
            $user->setStatus($userData['status']);
            $user->setRole($role);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LoadRoleFixture::class
        ];
    }
}