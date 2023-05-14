<?php

namespace App\Http\Controllers\Users\User\Permissions;

use Core\Auth\Infrastructure\AddPermissionToUserHandler as InfrastructureAddPermissionToUserHandler;
use Core\Auth\Infrastructure\Requests\AddPermissionToUserHandlerRequest;
use Illuminate\Http\Response;

class AddPermissionToUserHandler
{
    private InfrastructureAddPermissionToUserHandler $addPermissionToUserHandler;

    public function __construct(InfrastructureAddPermissionToUserHandler $addPermissionToUserHandler)
    {
        $this->addPermissionToUserHandler = $addPermissionToUserHandler;
    }

    public function __invoke(string $userID, AddPermissionToUserHandlerRequest $request): Response
    {
        return $this->addPermissionToUserHandler->__invoke($userID, $request);
    }
}
