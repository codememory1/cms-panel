<?php

namespace App\Controller\Api\V1;

use App\Attribute\Authorization;
use App\Enum\PermissionEnum;
use App\Repository\ActionLogRepository;
use App\ResponseData\ActionLogResponseData;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\HttpResponseCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/action-log')]
class ActionLogController extends AbstractController
{
    #[Route('/all', methods: Request::METHOD_GET)]
    #[Authorization(PermissionEnum::ALL_ACTION_LOGS)]
    public function list(ActionLogResponseData $responseData, ActionLogRepository $actionLogRepository): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $actionLogRepository->findAll());
    }
}