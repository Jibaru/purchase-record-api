<?php

namespace Core\Auth\Infrastructure\Repositories;

use Carbon\Carbon;
use Core\Auth\Domain\Entities\Permission;
use Core\Auth\Domain\Entities\User;
use Core\Auth\Domain\Entities\ValueObjects\PermissionName;
use Core\Auth\Domain\Entities\ValueObjects\UserEmail;
use Core\Auth\Domain\Entities\ValueObjects\UserID;
use Core\Auth\Domain\Entities\ValueObjects\UserName;
use Core\Auth\Domain\Entities\ValueObjects\UserPassword;
use Core\Auth\Domain\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MySqlUserRepository implements UserRepository
{
    public function store(User $user): void
    {
        $userData = $user->toArray();
        unset($userData['permissions']);
        DB::table('users')->insert($userData);
    }

    /**
     * @param UserID $userID
     * @return User
     * @throws Exception
     */
    public function getByUserID(UserID $userID): User
    {
        $user = DB::table('users')->where('id', $userID->value)->first();

        if (is_null($user)) {
            throw new Exception('user not found');
        }

        $permissions = collect(
                DB::table('permissions')->where('user_id', $userID->value)->get()
            )
            ->map(fn ($permission) => new Permission(new PermissionName($permission->name)))
            ->toArray();

        return new User(
            $userID,
            new UserName($user->name),
            new UserEmail($user->email),
            UserPassword::fromHashed($user->password),
            Carbon::parse($user->created_at),
            Carbon::parse($user->updated_at),
            $permissions
        );
    }

    /**
     * @param UserEmail $userEmail
     * @return User
     * @throws Exception
     */
    public function getByUserEmail(UserEmail $userEmail): User
    {
        $user = DB::table('users')->where('email', $userEmail->value)->first();

        if (is_null($user)) {
            throw new Exception('user not found');
        }

        $permissions = collect(
                DB::table('permissions')->where('user_id', $user->id)->get()
            )
            ->map(fn ($permission) => new Permission(new PermissionName($permission->name)))
            ->toArray();

        return new User(
            new UserID($user->id),
            new UserName($user->name),
            new UserEmail($user->email),
            UserPassword::fromHashed($user->password),
            Carbon::parse($user->created_at),
            Carbon::parse($user->updated_at),
            $permissions,
        );
    }

    public function existsUserByEmail(UserEmail $userEmail): bool
    {
        return DB::table('users')->where('email', $userEmail->value)->exists();
    }

    public function getUsers(int $page, ?int $perPage = null): array
    {
        if (is_null($perPage)) {
            return DB::table('users')
                ->orderByDesc('created_at')
                ->get()
                ->reduce(function (array &$users, $user) {
                    $permissions = DB::table('permissions')
                        ->where('user_id', $user->id)
                        ->get()
                        ->map(fn ($permission) => new Permission(new PermissionName($permission->name)))
                        ->toArray();

                    $users[] = new User(
                        new UserID($user->id),
                        new UserName($user->name),
                        new UserEmail($user->email),
                        UserPassword::fromHashed($user->password),
                        Carbon::parse($user->created_at),
                        Carbon::parse($user->updated_at),
                        $permissions,
                    );
                    return $users;
                }, []);
        }

        $users = DB::table('users')
            ->orderByDesc('created_at')
            ->paginate($perPage, '*', 'page', $page)
            ->items();

        if (empty($users)) {
            return [];
        }

        return collect($users)->reduce(function (array &$users, $user) {
            $permissions = DB::table('permissions')
                ->where('user_id', $user->id)
                ->get()
                ->map(fn ($permission) => new Permission(new PermissionName($permission->name)))
                ->toArray();

            $users[] = new User(
                new UserID($user->id),
                new UserName($user->name),
                new UserEmail($user->email),
                UserPassword::fromHashed($user->password),
                Carbon::parse($user->created_at),
                Carbon::parse($user->updated_at),
                $permissions,
            );
            return $users;
        }, []);
    }

    public function getTotalPages(int $perPage): int
    {
        $total = DB::table('users')->count('id');
        return ceil($total / $perPage);
    }
}
