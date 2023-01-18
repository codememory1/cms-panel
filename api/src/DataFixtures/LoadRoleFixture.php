<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\RolePermission;
use App\Enum\PermissionEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class LoadRoleFixture extends Fixture implements DependentFixtureInterface
{
    private array $roles = [
        [
            'title' => 'DEVELOPER',
            'permissions' => []
        ]
    ];

    public function __construct()
    {
        $this->roles[0]['permissions'] = PermissionEnum::cases(); // Add all permissions to developer role
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->roles as $roleData) {
            $role = new Role();

            $role->setTitle($roleData['title']);

            foreach ($roleData['permissions'] as $permissionData) {
                /** @var Permission $permission */
                $permission = $this->getReference("p-{$permissionData->name}");
                $rolePermission = new RolePermission();

                $rolePermission->setPermission($permission);

                $role->addPermission($rolePermission);
            }

            $manager->persist($role);

            $this->addReference("r-{$roleData['title']}", $role);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LoadPermissionFixture::class
        ];
    }
}