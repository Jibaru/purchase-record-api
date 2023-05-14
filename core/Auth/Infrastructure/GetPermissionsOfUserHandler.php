<?php

namespace Core\Auth\Infrastructure;

use Core\Auth\Domain\Entities\ValueObjects\UserID;
use Core\Auth\Domain\Repositories\UserRepository;
use Illuminate\Http\Response;

class GetPermissionsOfUserHandler
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(string $userID): Response
    {
        $user = $this->userRepository->getByUserID(new UserID($userID));

        return response(
            [
                'data' => $user->permissionsToArray(),
            ],
            200
        );
    }
}
