<?php

namespace App\DataFixtures;

use App\Entity\Bank;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class LoadBankFixture extends Fixture
{
    private array $banks = [
        [
            'title' => 'СберБанк',
            'number' => 900
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->banks as $bankData) {
            $bank = new Bank();

            $bank->setTrackActivities(false);
            $bank->setTitle($bankData['title']);
            $bank->setNumber($bankData['number']);

            $manager->persist($bank);

            $this->addReference("bn-{$bankData['number']}", $bank);
        }

        $manager->flush();
    }
}