<?php

namespace Core\Auth\Application\UseCases;

use Core\Auth\Domain\Entities\User;
use Core\Auth\Domain\Entities\ValueObjects\UserEmail;
use Core\Auth\Domain\Entities\ValueObjects\UserPassword;
use Core\Auth\Domain\Entities\ValueObjects\UserToken;
use Core\Auth\Domain\Repositories\UserRepository;
use Exception;

class CreateUserTokenUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserEmail $email
     * @param string $supposedPassword
     * @return UserToken
     * @throws Exception
     */
    public function __invoke(UserEmail $email, string $supposedPassword): UserToken
    {
        $user = $this->userRepository->getByUserEmail($email);
        return $user->generateToken($supposedPassword);
    }
}
