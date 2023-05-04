<?php

namespace Core\Auth\Infrastructure;

use Core\Auth\Application\UseCases\CreateUserUseCase;
use Core\Auth\Domain\Entities\ValueObjects\UserEmail;
use Core\Auth\Domain\Entities\ValueObjects\UserName;
use Core\Auth\Domain\Entities\ValueObjects\UserPassword;
use Core\Auth\Infrastructure\Requests\SignUpUserRequest;
use Exception;
use Illuminate\Http\Response;

class SignUpUserHandler
{
    private CreateUserUseCase $createUserUseCase;

    public function __construct(CreateUserUseCase $createUserUseCase)
    {
        $this->createUserUseCase = $createUserUseCase;
    }

    /**
     * @param SignUpUserRequest $request
     * @return Response
     * @throws Exception
     */
    public function __invoke(SignUpUserRequest $request): Response
    {
        $user = $this->createUserUseCase->__invoke(
            new UserName($request->input('name')),
            new UserEmail($request->input('email')),
            UserPassword::hashAndMake($request->input('password')),
        );

        return response([
            'data' => $user->toArray(),
        ], 200);
    }
}
