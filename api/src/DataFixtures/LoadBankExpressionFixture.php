<?php

namespace App\DataFixtures;

use App\Entity\Bank;
use App\Entity\BankExpression;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class LoadBankExpressionFixture extends Fixture implements DependentFixtureInterface
{
    private array $expression = [
        900 => [
            'transfer' => [
                '^(?<card_name>MIR)-(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Перевод|перевод)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>МИР)(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Перевод|перевод)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>VISA)-(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Перевод|перевод)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>VISA)(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Перевод|перевод)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>MIR)-(?<card_number>[0-9]{4})\s(?<date>[0-9]{2}:[0-9]{2})\s.*\s(?<sum>[0-9\.]+)р\.$',
                '^(?<card_name>МИР)(?<card_number>[0-9]{4})\s(?<date>[0-9]{2}:[0-9]{2})\s.*\s(?<sum>[0-9\.]+)р\.$',
                '^(?<card_name>VISA)-(?<card_number>[0-9]{4})\s(?<date>[0-9]{2}:[0-9]{2})\s.*\s(?<sum>[0-9\.]+)р\.$',
                '^(?<card_name>VISA)(?<card_number>[0-9]{4})\s(?<date>[0-9]{2}:[0-9]{2})\s.*\s(?<sum>[0-9\.]+)р\.$',
                '^(Перевод|перевод)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс)\s(?<card_name>MIR)-(?<card_number>[0-9]{4}):\s(?<balance>[0-9\.]+)р$',
                '^(Перевод|перевод)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс)\s(?<card_name>МИР)(?<card_number>[0-9]{4}):\s(?<balance>[0-9\.]+)р$',
                '^(Перевод|перевод)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс)\s(?<card_name>VISA)-(?<card_number>[0-9]{4}):\s(?<balance>[0-9\.]+)р$',
                '^(Перевод|перевод)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс)\s(?<card_name>VISA)(?<card_number>[0-9]{4}):\s(?<balance>[0-9\.]+)р$',
            ],
            'enrollment' => [
                '^(?<card_name>MIR)-(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\sзачисление\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>МИР)(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\sзачисление\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>VISA)-(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\sзачисление\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>VISA)(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\sзачисление\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
            ],
            'payment' => [
                '^(?<card_name>MIR)-(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Оплата|оплата)\s(?<sum>[0-9\.]+)р\s(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>МИР)(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Оплата|оплата)\s(?<sum>[0-9\.]+)р\s(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>VISA)-(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Оплата|оплата)\s(?<sum>[0-9\.]+)р\s(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>VISA)(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Оплата|оплата)\s(?<sum>[0-9\.]+)р\s(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
            ],
            'purchase' => [
                '^(?<card_name>MIR)-(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Покупка|покупка)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>МИР)(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Покупка|покупка)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>VISA)-(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Покупка|покупка)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>VISA)(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Покупка|покупка)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
            ]
        ]
    ];

    public function getDependencies(): array
    {
        return [
            LoadBankFixture::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->expression as $bankNumber => $expressions) {
            /** @var Bank $bank */
            $bank = $this->getReference("bn-$bankNumber");
            $bankExpression = new BankExpression();

            $bankExpression->setTrackActivities(false);
            $bankExpression->setBank($bank);
            $bankExpression->setTransfer($expressions['transfer']);
            $bankExpression->setEnrollment($expressions['enrollment']);
            $bankExpression->setPayment($expressions['payment']);
            $bankExpression->setPurchase($expressions['purchase']);

            $manager->persist($bankExpression);
        }

        $manager->flush();
    }
}