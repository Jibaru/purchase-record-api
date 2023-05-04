<?php

namespace Core\Auth\Infrastructure;

use Core\Auth\Application\UseCases\CreateUserTokenUseCase;
use Core\Auth\Domain\Entities\ValueObjects\UserEmail;
use Core\Auth\Infrastructure\Requests\LogInUserRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LogInUserHandler
{
    private CreateUserTokenUseCase $createUserTokenUseCase;

    public function __construct(CreateUserTokenUseCase $createUserTokenUseCase)
    {
        $this->createUserTokenUseCase = $createUserTokenUseCase;
    }

    /**
     * @param LogInUserRequest $request
     * @return Response
     * @throws Exception
     */
    public function __invoke(LogInUserRequest $request): Response
    {
        $token = $this->createUserTokenUseCase->__invoke(
            new UserEmail($request->input('email')),
            $request->input('password')
        );

        return response([
            'data' => $token->toJWT(),
        ], 200);
    }
}
