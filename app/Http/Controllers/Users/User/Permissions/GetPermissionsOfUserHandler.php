<?php

namespace App\Http\Controllers\Users\User\Permissions;

use Core\Auth\Infrastructure\GetPermissionsOfUserHandler as InfrastructureGetPermissionsOfUserHandler;
use Illuminate\Http\Response;

class GetPermissionsOfUserHandler
{
    private InfrastructureGetPermissionsOfUserHandler $getPermissionsOfUserHandler;

    public function __construct(InfrastructureGetPermissionsOfUserHandler $getPermissionsOfUserHandler)
    {
        $this->getPermissionsOfUserHandler = $getPermissionsOfUserHandler;
    }

    public function __invoke(string $userID): Response
    {
        return $this->getPermissionsOfUserHandler->__invoke($userID);
    }
}
