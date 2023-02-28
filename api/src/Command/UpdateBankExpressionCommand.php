<?php

namespace App\Command;

use App\Entity\BankExpression;
use App\Repository\BankRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    'app:update-bank-expressions',
    'Update bank expressions'
)]
final class UpdateBankExpressionCommand extends Command
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
                '^(?<card_name>[a-zA-Zа-яА-ЯЁё]+)-?(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Перевод|перевод)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$',
                '^(?<card_name>[a-zA-Zа-яА-ЯЁё]+)-?(?<card_number>[0-9]+)\s(?<date>[0-9]{2}:[0-9]{2})\s(Перевод|перевод)\s(?<sum>[0-9\.]+)р((\s.*\s)|(\s))(Баланс|баланс):\s(?<balance>[0-9\.]+)р$'
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

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly BankRepository $bankRepository
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->expression as $bankNumber => $expressions) {
            $bank = $this->bankRepository->findByNumber($bankNumber);
            $bankExpression = new BankExpression();

            $bankExpression->setTrackActivities(false);
            $bankExpression->setBank($bank);
            $bankExpression->setTransfer($expressions['transfer']);
            $bankExpression->setEnrollment($expressions['enrollment']);
            $bankExpression->setPayment($expressions['payment']);
            $bankExpression->setPurchase($expressions['purchase']);

            $this->em->persist($bankExpression);
        }

        $this->em->flush();

        $io->info('Success update bank expressions');

        return self::SUCCESS;
    }
}