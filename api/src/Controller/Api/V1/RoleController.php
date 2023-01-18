<?php

namespace App\Controller\Api\V1;

use App\Annotation\EntityNotFound;
use App\Attribute\Authorization;
use App\Dto\Transformer\CreateRoleTransformer;
use App\Dto\Transformer\RolePermissionTransformer;
use App\Dto\Transformer\UpdateRoleTransformer;
use App\Entity\Role;
use App\Entity\RolePermission;
use App\Enum\PermissionEnum;
use App\Exception\EntityNotFoundException;
use App\Repository\RoleRepository;
use App\ResponseData\RolePermissionResponseData;
use App\ResponseData\RoleResponseData;
use App\Rest\Controller\AbstractController;
use App\Rest\Response\Interfaces\HttpResponseCollectorInterface;
use App\UseCase\Role\CreateRole;
use App\UseCase\Role\DeleteRole;
use App\UseCase\Role\Permission\AddPermissionToRole;
use App\UseCase\Role\Permission\DeletePermissionToRole;
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
    public function create(RoleResponseData $responseData, CreateRoleTransformer $transformer, CreateRole $createRole): HttpResponseCollectorInterface
    {
        return $this->responseData($responseData, $createRole->process($transformer->transformFromRequest()));
    }

    #[Route('/permission/{rolePermission_id<.+>}/delete', methods: Request::METHOD_DELETE)]
    #[Authorization(PermissionEnum::DELETE_PERMISSION_TO_ROLE)]
    public function deletePermission(
        #[EntityNotFound(EntityNotFoundException::class, 'rolePermission')] RolePermission $rolePermission,
        RolePermissionResponseData $responseData,
        DeletePermissionToRole $deletePermissionToRole
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $deletePermissionToRole->process($rolePermission));
    }

    #[Route('/{role_id<.+>}/edit', methods: Request::METHOD_PUT)]
    #[Authorization(PermissionEnum::UPDATE_ROLE)]
    public function update(
        #[EntityNotFound(EntityNotFoundException::class, 'role')] Role $role,
        RoleResponseData $responseData,
        UpdateRoleTransformer $transformer,
        UpdateRole $updateRole
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $updateRole->process($transformer->transformFromRequest($role)));
    }

    #[Route('/{role_id<.+>}/delete', methods: Request::METHOD_DELETE)]
    #[Authorization(PermissionEnum::DELETE_ROLE)]
    public function delete(
        #[EntityNotFound(EntityNotFoundException::class, 'role')] Role $role,
        RoleResponseData $responseData,
        DeleteRole $deleteRole
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $deleteRole->process($role));
    }

    #[Route('/{role_id<.+>}/permission/add', methods: Request::METHOD_POST)]
    #[Authorization(PermissionEnum::ADD_PERMISSION_TO_ROLE)]
    public function addPermission(
        #[EntityNotFound(EntityNotFoundException::class, 'role')] Role $role,
        RolePermissionResponseData $responseData,
        RolePermissionTransformer $transformer,
        AddPermissionToRole $addPermissionToRole
    ): HttpResponseCollectorInterface {
        return $this->responseData($responseData, $addPermissionToRole->process($role, $transformer->transformFromRequest()));
    }
}