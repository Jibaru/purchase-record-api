<?php

namespace Core\Auth\Infrastructure\Repositories;

use Carbon\Carbon;
use Core\Auth\Domain\Entities\User;
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
        DB::table('users')->insert($user->toArray());
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

        return new User(
            $userID,
            new UserName($user->name),
            new UserEmail($user->email),
            UserPassword::fromHashed($user->password),
            Carbon::parse($user->created_at),
            Carbon::parse($user->updated_at),
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

        return new User(
            new UserID($user->id),
            new UserName($user->name),
            new UserEmail($user->email),
            UserPassword::fromHashed($user->password),
            Carbon::parse($user->created_at),
            Carbon::parse($user->updated_at),
        );
    }

    public function existsUserByEmail(UserEmail $userEmail): bool
    {
        return DB::table('users')->where('email', $userEmail->value)->exists();
    }
}
