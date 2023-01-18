<?php

namespace App\Controller\Api\V1;

use App\Annotation\EntityNotFound;
use App\Attribute\Authorization;
use App\Dto\Transformer\UserPasswordTransformer;
use App\Dto\Transformer\UserTransformer;
use App\Entity\User;
use App\Enum\PermissionEnum;
use App\Exception\EntityNotFoundException;
use App\Repository\UserRepository;
use App\ResponseData\UserResponseData;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\HttpResponseCollectorInterface;
use App\UseCase\User\CreateUser;
use App\UseCase\User\DeleteUser;
use App\UseCase\User\UpdateUser;
use App\UseCase\User\UpdateUserPassword;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/all', methods: Request::METHOD_GET)]
    #[Authorization(PermissionEnum::ALL_USERS)]
    public function list(UserResponseData $responseData, UserRepository $userRepository): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $userRepository->findAll());
    }

    #[Route('/create', methods: Request::METHOD_POST)]
    #[Authorization(PermissionEnum::CREATE_USER)]
    public function create(
        UserResponseData $responseData,
        UserTransformer $transformer,
        CreateUser $createUser
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $createUser->process($transformer->transformFromRequest()));
    }

    #[Route('/{user_id<.+>}/edit', methods: Request::METHOD_PUT)]
    #[Authorization(PermissionEnum::UPDATE_USER)]
    public function update(
        #[EntityNotFound(EntityNotFoundException::class, 'user')] User $user,
        UserResponseData $responseData,
        UserTransformer $transformer,
        UpdateUser $updateUser
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $updateUser->process($transformer->transformFromRequest($user)));
    }

    #[Route('/{user_id<.+>}/delete', methods: Request::METHOD_DELETE)]
    #[Authorization(PermissionEnum::DELETE_USER)]
    public function delete(
        #[EntityNotFound(EntityNotFoundException::class, 'user')] User $user,
        UserResponseData $responseData,
        DeleteUser $deleteUser
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $deleteUser->process($user));
    }

    #[Route('/{user_id<.+>}/password/edit', methods: Request::METHOD_PATCH)]
    #[Authorization(PermissionEnum::UPDATE_USER)]
    public function updatePassword(
        #[EntityNotFound(EntityNotFoundException::class, 'user')] User $user,
        UserResponseData $responseData,
        UserPasswordTransformer $transformer,
        UpdateUserPassword $updateUserPassword
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $updateUserPassword->process($transformer->transformFromRequest($user)));
    }
}