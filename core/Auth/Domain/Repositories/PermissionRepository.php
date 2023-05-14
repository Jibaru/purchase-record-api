<?php

namespace Core\Auth\Domain\Repositories;

use Core\Auth\Domain\Entities\Permission;
use Core\Auth\Domain\Entities\ValueObjects\UserID;

interface PermissionRepository
{
    public function store(Permission $permission, UserID $userID): void;
    public function delete(Permission $permission, UserID $userID): void;

    /**
     * @param UserID $userID
     * @return Permission[]
     */
    public function getByUserID(UserID $userID): array;
}
