<?php

namespace App\Http\Controllers\Users;

use Core\Auth\Infrastructure\LogInUserHandler as InfrastructureLogInUserHandler;
use Core\Auth\Infrastructure\Requests\LogInUserRequest;
use Exception;
use Illuminate\Http\Response;

class LogInUserHandler
{
    private InfrastructureLogInUserHandler $logInUserHandler;

    public function __construct(InfrastructureLogInUserHandler $logInUserHandler)
    {
        $this->logInUserHandler = $logInUserHandler;
    }

    /**
     * @param LogInUserRequest $request
     * @return Response
     * @throws Exception
     */
    public function __invoke(LogInUserRequest $request): Response
    {
        return $this->logInUserHandler->__invoke($request);
    }
}
