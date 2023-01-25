<?php

namespace App\Controller\Api\V1;

use App\Dto\Transformer\AuthorizationTransformer;
use App\ResponseData\UserResponseData;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\SuccessHttpResponseCollectorInterface;
use App\Security\Authorization\Authorization;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Attribute\Authorization as AuthorizationAttribute;

#[Route('/security')]
class SecurityController extends AbstractController
{
    #[Route('/auth', methods: Request::METHOD_POST)]
    public function auth(AuthorizationTransformer $transformer, Authorization $authorization): SuccessHttpResponseCollectorInterface
    {
        return $this->response([
            'access_token' => $authorization->authorize($transformer->transformFromRequest())
        ]);
    }

    #[Route('/user/info', methods: Request::METHOD_GET)]
    #[AuthorizationAttribute]
    public function getUserInfo(UserResponseData $responseData): SuccessHttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $this->authorizedUser->getUser());
    }
}