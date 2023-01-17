<?php

namespace App\Controller\Api\V1;

use App\Dto\Transformer\AuthorizationTransformer;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\SuccessHttpResponseCollectorInterface;
use App\Security\Authorization\Authorization;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/security')]
class SecurityController extends AbstractController
{
    #[Route('/auth', methods: Request::METHOD_POST)]
    public function auth(AuthorizationTransformer $transformer, Authorization $authorization): SuccessHttpResponseCollectorInterface
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        dd($phoneUtil->parse('+380682423643'));

        return $this->response([
            'access_token' => $authorization->authorize($transformer->transformFromRequest())
        ]);
    }
}