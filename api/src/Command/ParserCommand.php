<?php

namespace App\Command;

use App\Entity\Phone;
use App\Entity\Transaction;
use App\Enum\PhoneStatusEnum;
use App\Repository\BankRepository;
use App\Repository\PhoneRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use libphonenumber\PhoneNumber;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

#[AsCommand(
    'app:worker:parser',
    'Постоянно работающий worker, который парсит SMS-информацию'
)]
final class ParserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PhoneRepository $phoneRepository,
        private readonly BankRepository $bankRepository,
        private readonly TransactionRepository $transactionRepository,
        private readonly HttpClientInterface $client,
        private readonly string $smsApiKey
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Worker успешно запущен');

        while (true) {
            sleep(1);

            $io->info('Запускаем итерацию по всем доступным телефонам');

            /** @var Phone $phone */
            foreach ($this->phoneRepository->getAllowed() as $phone) {
                $phoneNumber = $this->collectPhone($phone->getNumber());

                $io->info("Начинаем парсинг по номеру телефона: $phoneNumber");

                $content = $this->getSmsContent($this->collectUrl($phoneNumber));

                $io->info("Получаем контент страницы для телефона: $phoneNumber");

                if (null !== $content) {
                    $io->info('Получили контент страницы, начинаем получение всех записей');

                    $records = $this->getRecords(new Crawler($content));

                    foreach ($records as $i => $record) {
                        $number = $i + 1;
                        $record['phone_number'] = $phoneNumber;
                        $record['allowed_phone'] = $phone;

                        $io->info("Парсим $number запись и записываем в базу данных");
                        $this->parseRecord($record, $records[array_key_last($records)]);
                    }
                } else {
                    $io->info('Получили контент, номер недействителен - меняем статус');

                    $phone->setStatus(PhoneStatusEnum::NOT_ALLOWED);

                    $this->em->flush();
                }
            }

            $io->info('Закончили итерацию по всем доступным телефонам');

            $this->em->clear();
        }
    }

    private function collectUrl(string $phone): string
    {
        return "https://sms.cryptosyndicate.cc/vsimGetSMS.php?number=$phone&apikey=$this->smsApiKey&view=user";
    }

    private function collectPhone(PhoneNumber $phone): string
    {
        return "{$phone->getCountryCode()}{$phone->getNationalNumber()}";
    }

    private function getSmsContent(string $url): ?string
    {
        try {
            $response = $this->client->request('GET', $url);

            try {
                json_decode($response->getContent(), flags: JSON_THROW_ON_ERROR);

                return null;
            } catch (Exception) {
                return $response->getContent();
            }
        } catch (Throwable) {
            sleep(30);

            return null;
        }
    }

    private function getRecords(Crawler $crawler): array
    {
        $records = [];

        $crawler
            ->filter('table > tbody > tr')
            ->each(static function (Crawler $crawler) use (&$records) {
                $record = [];

                $crawler->filter('td')->each(static function (Crawler $crawler, int $i) use (&$record) {
                    match ($i) {
                        0 => $record['date'] = $crawler->text(),
                        1 => $record['bank'] = $crawler->text(),
                        2 => $record['message'] = $crawler->text(),
                        default => null
                    };
                });

                $records[] = $record;
            });

        return $records;
    }

    private function parseRecord(array $record, array $lastRecord): void
    {
        $bank = $this->bankRepository->findByNumber((int) $record['bank']);
        $messageIsParsed = false;

        if (null !== $bank && null !== $bank->getExpression()) {
            $this->parseMessageByType($messageIsParsed, $bank->getExpression()->getTransfer(), $record, $lastRecord, 'transfer');
            $this->parseMessageByType($messageIsParsed, $bank->getExpression()->getEnrollment(), $record, $lastRecord, 'enrollment');
            $this->parseMessageByType($messageIsParsed, $bank->getExpression()->getPayment(), $record, $lastRecord, 'payment');
            $this->parseMessageByType($messageIsParsed, $bank->getExpression()->getPurchase(), $record, $lastRecord, 'purchase');
        }
    }

    private function parseMessageByType(bool &$isParsed, array $expressions, array $record, array $lastRecord, string $type): void
    {
        if (!$isParsed) {
            $isParsed = $this->parseMessage($expressions, $record, $lastRecord, $type);
        }
    }

    private function parseMessage(array $expressions, array $record, array $lastRecord, string $type): bool
    {
        foreach ($expressions as $expression) {
            if (1 === preg_match("/$expression/", $record['message'], $match)) {
                $this->loadTransaction($record, $lastRecord, $match, $type);

                return true;
            }
        }

        return false;
    }

    private function loadTransaction(array $record, array $lastRecord, array $match, string $type): void
    {
        $allowedPhone = $record['allowed_phone'];

        unset($record['allowed_phone']);

        $hash = $this->generateRecordHash($record);

        if (null === $this->transactionRepository->findByHash($hash)) {
            $transaction = new Transaction();

            $transaction->setPhone($allowedPhone);
            $transaction->setHash($hash);
            $transaction->setType($type);
            $transaction->setCard([
                'name' => $match['card_name'],
                'number' => $match['card_number']
            ]);

            $transaction->setCompletedOnTime($record['date']);
            $transaction->setSum($match['sum'] ?? null);

            if (array_key_exists('balance', $match) && $record['date'] >= $lastRecord['date']) {
                $allowedPhone->getBalance()->setBalance($match['balance']);
            }

            $this->em->persist($transaction);
            $this->em->flush();
        }
    }

    private function generateRecordHash(array $record): string
    {
        return sha1(json_encode($record));
    }
}