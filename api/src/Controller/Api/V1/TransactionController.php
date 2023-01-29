<?php

namespace App\Controller\Api\V1;

use App\Attribute\Authorization;
use App\Enum\PermissionEnum;
use App\Repository\TransactionRepository;
use App\ResponseData\TransactionResponseData;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\HttpResponseCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transaction')]
final class TransactionController extends AbstractController
{
    #[Route('/all', methods: Request::METHOD_GET)]
    #[Authorization(PermissionEnum::ALL_TRANSACTIONS)]
    public function list(TransactionResponseData $responseData, TransactionRepository $transactionRepository): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $transactionRepository->findAll());
    }
}