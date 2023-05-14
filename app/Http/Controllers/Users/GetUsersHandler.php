<?php

namespace App\Http\Controllers\Users;

use Core\Auth\Infrastructure\GetUsersHandler as InfrastructureGetUsersHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetUsersHandler
{
    private InfrastructureGetUsersHandler $getUsersHandler;

    public function __construct(InfrastructureGetUsersHandler $getUsersHandler)
    {
        $this->getUsersHandler = $getUsersHandler;
    }

    public function __invoke(Request $request): Response
    {
        return $this->getUsersHandler->__invoke($request);
    }
}
