<?php

namespace App\Controller\Api\V1;

use App\Attribute\EntityNotFound;
use App\Attribute\Authorization;
use App\Dto\Transformer\RoleTransformer;
use App\Entity\Role;
use App\Enum\PermissionEnum;
use App\Exception\EntityNotFoundException;
use App\Repository\RoleRepository;
use App\ResponseData\RoleResponseData;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\HttpResponseCollectorInterface;
use App\UseCase\Role\CreateRole;
use App\UseCase\Role\DeleteRole;
use App\UseCase\Role\UpdateRole;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/role')]
class RoleController extends AbstractController
{
    #[Route('/all', methods: Request::METHOD_GET)]
    #[Authorization(PermissionEnum::ALL_ROLES)]
    public function list(RoleResponseData $responseData, RoleRepository $roleRepository): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $roleRepository->findAll());
    }

    #[Route('/create', methods: Request::METHOD_POST)]
    #[Authorization(PermissionEnum::CREATE_ROLE)]
    public function create(RoleResponseData $responseData, RoleTransformer $transformer, CreateRole $createRole): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $createRole->process($transformer->transformFromRequest()));
    }

    #[Route('/{role_id<[^\/]+>}/edit', methods: Request::METHOD_PUT)]
    #[Authorization(PermissionEnum::UPDATE_ROLE)]
    public function update(
        #[EntityNotFound(EntityNotFoundException::class, 'role')] Role $role,
        RoleResponseData $responseData,
        RoleTransformer $transformer,
        UpdateRole $updateRole
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $updateRole->process($transformer->transformFromRequest($role)));
    }

    #[Route('/{role_id<[^\/]+>}/delete', methods: Request::METHOD_DELETE)]
    #[Authorization(PermissionEnum::DELETE_ROLE)]
    public function delete(
        #[EntityNotFound(EntityNotFoundException::class, 'role')] Role $role,
        RoleResponseData $responseData,
        DeleteRole $deleteRole
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $deleteRole->process($role));
    }
}