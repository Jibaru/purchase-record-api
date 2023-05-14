<?php

namespace Core\Auth\Infrastructure;

use Core\Auth\Application\UseCases\CreateUserTokenUseCase;
use Core\Auth\Domain\Entities\ValueObjects\UserEmail;
use Core\Auth\Domain\Entities\ValueObjects\UserID;
use Core\Auth\Domain\Repositories\PermissionRepository;
use Core\Auth\Infrastructure\Requests\LogInUserRequest;
use Core\Auth\Infrastructure\Resources\PermissionResource;
use Exception;
use Illuminate\Http\Response;

class LogInUserHandler
{
    private CreateUserTokenUseCase $createUserTokenUseCase;
    private PermissionRepository $permissionRepository;

    public function __construct(
        CreateUserTokenUseCase $createUserTokenUseCase,
        PermissionRepository $permissionRepository,
    ) {
        $this->createUserTokenUseCase = $createUserTokenUseCase;
        $this->permissionRepository = $permissionRepository;
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

        $permissions = $this->permissionRepository->getByUserID(new UserID($token->subject()));

        return response([
            'data' => array_merge(
                $token->toArray(),
                [
                    'permissions' => PermissionResource::collection($permissions),
                ]
            ),
        ], 200);
    }
}
