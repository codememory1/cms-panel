<?php

namespace App\Controller\Api\V1;

use App\Annotation\EntityNotFound;
use App\Attribute\Authorization;
use App\Dto\Transformer\BankExpressionTransformer;
use App\Dto\Transformer\BankTransformer;
use App\Entity\Bank;
use App\Enum\PermissionEnum;
use App\Exception\EntityNotFoundException;
use App\Repository\BankExpressionRepository;
use App\Repository\BankRepository;
use App\ResponseData\BankExpressionResponseData;
use App\ResponseData\BankResponseData;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\HttpResponseCollectorInterface;
use App\UseCase\Bank\CreateBank;
use App\UseCase\Bank\DeleteBank;
use App\UseCase\Bank\Expression\UpdateBankExpression;
use App\UseCase\Bank\UpdateBank;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bank')]
final class BankController extends AbstractController
{
    #[Authorization(PermissionEnum::ALL_BANKS)]
    #[Route('/all', methods: Request::METHOD_GET)]
    public function list(BankResponseData $responseData, BankRepository $bankRepository): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $bankRepository->findAll());
    }

    #[Authorization(PermissionEnum::CREATE_BANK)]
    #[Route('/create', methods: Request::METHOD_POST)]
    public function create(BankResponseData $responseData, BankTransformer $transformer, CreateBank $createBank): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $createBank->process($transformer->transformFromRequest()));
    }

    #[Authorization(PermissionEnum::UPDATE_BANK)]
    #[Route('/{bank_id<[^\/]+>}/edit', methods: Request::METHOD_PUT)]
    public function update(
        #[EntityNotFound(EntityNotFoundException::class, 'bank')] Bank $bank,
        BankResponseData $responseData,
        BankTransformer $transformer,
        UpdateBank $updateBank
    ): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $updateBank->process($transformer->transformFromRequest($bank)));
    }

    #[Authorization(PermissionEnum::DELETE_BANK)]
    #[Route('/{bank_id<[^\/]+>}/delete', methods: Request::METHOD_DELETE)]
    public function delete(
        #[EntityNotFound(EntityNotFoundException::class, 'bank')] Bank $bank,
        BankResponseData $responseData,
        DeleteBank $deleteBank
    ): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $deleteBank->process($bank));
    }

    #[Authorization(PermissionEnum::ALL_BANK_REGEXP)]
    #[Route('/{bank_id<[^\/]+>}/expression/all', methods: Request::METHOD_GET)]
    public function bankExpressionList(
        #[EntityNotFound(EntityNotFoundException::class, 'bank')] Bank $bank,
        BankExpressionResponseData $responseData
    ): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $bank->getExpression());
    }

    #[Authorization(PermissionEnum::ALL_BANK_REGEXP)]
    #[Route('/expression/all', methods: Request::METHOD_GET)]
    public function expressionList(BankExpressionResponseData $responseData, BankExpressionRepository $bankExpressionRepository): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $bankExpressionRepository->findAll());
    }

    #[Authorization(PermissionEnum::UPDATE_BANK_REGEXP)]
    #[Route('/{bank_id<[^\/]+>}/expression/edit', methods: Request::METHOD_PUT)]
    public function updateExpression(
        #[EntityNotFound(EntityNotFoundException::class, 'bank')] Bank $bank,
        BankExpressionResponseData $responseData,
        BankExpressionTransformer $transformer,
        UpdateBankExpression $updateBankExpression
    ): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $updateBankExpression->process($transformer->transformFromRequest($bank->getExpression())));
    }
}