<?php

namespace Core\Auth\Domain\Repositories;

use Core\Auth\Domain\Entities\User;
use Core\Auth\Domain\Entities\ValueObjects\UserEmail;
use Core\Auth\Domain\Entities\ValueObjects\UserID;
use Exception;

interface UserRepository
{
    public function store(User $user): void;

    /**
     * @param UserID $userID
     * @return User
     * @throws Exception
     */
    public function getByUserID(UserID $userID): User;

    /**
     * @param UserEmail $userEmail
     * @return User
     * @throws Exception
     */
    public function getByUserEmail(UserEmail $userEmail): User;
    public function existsUserByEmail(UserEmail $userEmail): bool;
}
