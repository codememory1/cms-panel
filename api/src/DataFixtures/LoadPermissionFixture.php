<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use App\Enum\PermissionEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class LoadPermissionFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (PermissionEnum::cases() as $case) {
            $permission = new Permission();

            $permission->setKey($case->name);
            $permission->setTitle($case->value);

            $manager->persist($permission);

            $this->addReference("p-{$case->name}", $permission);
        }

        $manager->flush();
    }
}