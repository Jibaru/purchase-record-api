<?php

namespace Core\Auth\Infrastructure;

use Core\Auth\Domain\Entities\Permission;
use Core\Auth\Domain\Entities\ValueObjects\PermissionName;
use Core\Auth\Domain\Entities\ValueObjects\UserID;
use Core\Auth\Domain\Repositories\PermissionRepository;
use Core\Auth\Domain\Repositories\UserRepository;
use Core\Auth\Infrastructure\Requests\AddPermissionToUserHandlerRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AddPermissionToUserHandler
{
    private PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function __invoke(string $userID, AddPermissionToUserHandlerRequest $request): Response
    {
        $permission = new Permission(new PermissionName($request->input('name')));
        $this->permissionRepository->store($permission, new UserID($userID));

        return response(
            [
                'message' => 'ok',
            ],
            201
        );
    }
}
