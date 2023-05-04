<?php

namespace Core\Auth\Application\UseCases;

use Carbon\Carbon;
use Core\Auth\Domain\Entities\User;
use Core\Auth\Domain\Entities\ValueObjects\UserEmail;
use Core\Auth\Domain\Entities\ValueObjects\UserID;
use Core\Auth\Domain\Entities\ValueObjects\UserName;
use Core\Auth\Domain\Entities\ValueObjects\UserPassword;
use Core\Auth\Domain\Repositories\UserRepository;
use Exception;

class CreateUserUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserName $name
     * @param UserEmail $email
     * @param UserPassword $password
     * @return User
     * @throws Exception
     */
    public function __invoke(
        UserName $name,
        UserEmail $email,
        UserPassword $password
    ): User {
        if ($this->userRepository->existsUserByEmail($email)) {
            throw new Exception('user email already took');
        }


        $user = new User(
            UserID::empty(),
            $name,
            $email,
            $password,
            Carbon::now(),
            Carbon::now(),
        );

        $this->userRepository->store($user);

        return $user;
    }
}
