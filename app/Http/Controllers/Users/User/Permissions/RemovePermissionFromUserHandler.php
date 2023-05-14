<?php

namespace App\Http\Controllers\Users\User\Permissions;

use Core\Auth\Infrastructure\RemovePermissionFromUserHandler as InfrastructureRemovePermissionFromUserHandler;
use Core\Auth\Infrastructure\Requests\RemovePermissionToUserHandlerRequest;
use Illuminate\Http\Response;

class RemovePermissionFromUserHandler
{
    private InfrastructureRemovePermissionFromUserHandler $removePermissionFromUserHandler;

    public function __construct(InfrastructureRemovePermissionFromUserHandler $removePermissionFromUserHandler)
    {
        $this->removePermissionFromUserHandler = $removePermissionFromUserHandler;
    }

    public function __invoke(string $userID, RemovePermissionToUserHandlerRequest $request): Response
    {
        return $this->removePermissionFromUserHandler->__invoke($userID, $request);
    }
}
