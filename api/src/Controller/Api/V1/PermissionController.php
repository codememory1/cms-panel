<?php

namespace App\Controller\Api\V1;

use App\Attribute\Authorization;
use App\Enum\PermissionEnum;
use App\Repository\PermissionRepository;
use App\ResponseData\PermissionResponseData;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\HttpResponseCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/permission')]
class PermissionController extends AbstractController
{
    #[Route('/all', methods: Request::METHOD_GET)]
    #[Authorization(PermissionEnum::ALL_PERMISSIONS)]
    public function list(PermissionResponseData $responseData, PermissionRepository $permissionRepository): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $permissionRepository->findAll());
    }
}