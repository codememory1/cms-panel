<?php

namespace App\Controller\Api\V1;

use App\Attribute\Authorization;
use App\Attribute\EntityNotFound;
use App\Entity\Phone;
use App\Enum\PermissionEnum;
use App\Exception\EntityNotFoundException;
use App\Infrastructure\Doctrine\Paginator;
use App\Repository\TransactionRepository;
use App\ResponseData\TransactionResponseData;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\HttpResponseCollectorInterface;
use App\Rest\Response\Meta\ResponseMetaPagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transaction')]
final class TransactionController extends AbstractController
{
    #[Route('/all', methods: Request::METHOD_GET)]
    #[Authorization(PermissionEnum::ALL_TRANSACTIONS)]
    public function list(TransactionResponseData $responseData, TransactionRepository $transactionRepository, Paginator $paginator): HttpResponseCollectorInterface
    {
        $paginator->setQuery($transactionRepository->findAllQuery());

        return $this
            ->responseData($responseData, $paginator->getData())
            ->addMeta(new ResponseMetaPagination($paginator));
    }

    #[Route('/{phone_id<[^\/]+>}/all', methods: Request::METHOD_GET)]
    #[Authorization(PermissionEnum::ALL_TRANSACTIONS)]
    public function allByPhone(
        #[EntityNotFound(EntityNotFoundException::class, 'phone')] Phone $phone,
        TransactionResponseData $responseData,
        TransactionRepository $transactionRepository,
        Paginator $paginator
    ): HttpResponseCollectorInterface
    {
        $paginator->setQuery($transactionRepository->findAllQueryByPhone($phone));

        return $this
            ->responseData($responseData, $paginator->getData())
            ->addMeta(new ResponseMetaPagination($paginator));
    }
}