<?php

namespace Core\Auth\Infrastructure\Repositories;

use Core\Auth\Domain\Entities\Permission;
use Core\Auth\Domain\Entities\ValueObjects\PermissionName;
use Core\Auth\Domain\Entities\ValueObjects\UserID;
use Core\Auth\Domain\Repositories\PermissionRepository;
use Illuminate\Support\Facades\DB;

class MySqlPermissionRepository implements PermissionRepository
{
    public function store(Permission $permission, UserID $userID): void
    {
        DB::table('permissions')->insert(array_merge(
            $permission->toArray(),
            [
                'user_id' => $userID->value,
            ]
        ));
    }

    public function delete(Permission $permission, UserID $userID): void
    {
        DB::table('permissions')->where(array_merge(
            $permission->toArray(),
            [
                'user_id' => $userID->value,
            ]
        ))->delete();
    }

    /**
     * @param UserID $userID
     * @return Permission[]
     */
    public function getByUserID(UserID $userID): array
    {
        return collect(
                DB::table('permissions')->where('user_id', $userID->value)->get()
            )
            ->map(fn ($permission) => new Permission(new PermissionName($permission->name)))
            ->toArray();
    }
}