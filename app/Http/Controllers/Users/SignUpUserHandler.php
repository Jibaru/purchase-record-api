<?php

namespace App\Http\Controllers\Users;

use Core\Auth\Infrastructure\Requests\SignUpUserRequest;
use Core\Auth\Infrastructure\SignUpUserHandler as InfrastructureSignUpUserHandler;
use Exception;
use Illuminate\Http\Response;

class SignUpUserHandler
{
    private InfrastructureSignUpUserHandler $signUpUserHandler;

    public function __construct(InfrastructureSignUpUserHandler $signUpUserHandler)
    {
        $this->signUpUserHandler = $signUpUserHandler;
    }

    /**
     * @param SignUpUserRequest $request
     * @return Response
     * @throws Exception
     */
    public function __invoke(SignUpUserRequest $request): Response
    {
        return $this->signUpUserHandler->__invoke($request);
    }
}
