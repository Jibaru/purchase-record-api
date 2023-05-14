<?php

namespace Core\Auth\Infrastructure;

use Core\Auth\Domain\Entities\Permission;
use Core\Auth\Domain\Entities\ValueObjects\PermissionName;
use Core\Auth\Domain\Entities\ValueObjects\UserID;
use Core\Auth\Domain\Repositories\PermissionRepository;
use Core\Auth\Infrastructure\Requests\RemovePermissionToUserHandlerRequest;
use Illuminate\Http\Response;

class RemovePermissionFromUserHandler
{
    private PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function __invoke(string $userID, RemovePermissionToUserHandlerRequest $request): Response
    {
        $permission = new Permission(new PermissionName($request->input('name')));
        $this->permissionRepository->delete($permission, new UserID($userID));

        return response(
            [
                'message' => 'ok',
            ],
            200
        );
    }
}
