<?php

namespace App\Controller\Api\V1;

use App\Attribute\EntityNotFound;
use App\Attribute\Authorization;
use App\Dto\Transformer\PhoneTransformer;
use App\Entity\Phone;
use App\Enum\PermissionEnum;
use App\Exception\EntityNotFoundException;
use App\Repository\PhoneRepository;
use App\ResponseData\PhoneResponseData;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\HttpResponseCollectorInterface;
use App\UseCase\Phone\CreatePhone;
use App\UseCase\Phone\DeletePhone;
use App\UseCase\Phone\UpdatePhone;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/phone')]
class PhoneController extends AbstractController
{
    #[Route('/all', methods: Request::METHOD_GET)]
    #[Authorization(PermissionEnum::ALL_PHONES)]
    public function list(PhoneResponseData $responseData, PhoneRepository $phoneRepository): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $phoneRepository->findAll());
    }

    #[Route('/create', methods: Request::METHOD_POST)]
    #[Authorization(PermissionEnum::CREATE_PHONE)]
    public function create(PhoneResponseData $responseData, PhoneTransformer $transformer, CreatePhone $createPhone): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $createPhone->process($transformer->transformFromRequest()));
    }

    #[Route('/{phone_id<[^\/]+>}/edit', methods: Request::METHOD_PUT)]
    #[Authorization(PermissionEnum::UPDATE_PHONE)]
    public function update(
        #[EntityNotFound(EntityNotFoundException::class, 'phone')] Phone $phone,
        PhoneResponseData $responseData,
        PhoneTransformer $transformer,
        UpdatePhone $updatePhone
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $updatePhone->process($transformer->transformFromRequest($phone)));
    }

    #[Route('/{phone_id<[^\/]+>}/delete', methods: Request::METHOD_DELETE)]
    #[Authorization(PermissionEnum::DELETE_PHONE)]
    public function delete(
        #[EntityNotFound(EntityNotFoundException::class, 'phone')] Phone $phone,
        DeletePhone $deletePhone,
        PhoneResponseData $responseData
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $deletePhone->process($phone));
    }
}