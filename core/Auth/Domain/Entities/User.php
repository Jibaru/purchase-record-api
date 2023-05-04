<?php

namespace Core\Auth\Domain\Entities;

use Carbon\Carbon;
use Core\Auth\Domain\Entities\ValueObjects\UserEmail;
use Core\Auth\Domain\Entities\ValueObjects\UserID;
use Core\Auth\Domain\Entities\ValueObjects\UserName;
use Core\Auth\Domain\Entities\ValueObjects\UserPassword;
use Core\Auth\Domain\Entities\ValueObjects\UserToken;
use Exception;

class User
{
    private UserID $id;
    private UserName $name;
    private UserEmail $email;
    private UserPassword $password;
    private Carbon $createdAt;
    private Carbon $updatedAt;

    public function __construct(
        UserID $id,
        UserName $name,
        UserEmail $email,
        UserPassword $password,
        Carbon $createdAt,
        Carbon $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value,
            'name' => $this->name->value,
            'email' => $this->email->value,
            'password' => $this->password->hashedValue,
            'created_at' => $this->createdAt->toDateTimeString(),
            'updated_at' => $this->updatedAt->toDateTimeString(),
        ];
    }

    /**
     * @param string $supposedPassword
     * @return UserToken
     * @throws Exception
     */
    public function generateToken(string $supposedPassword): UserToken
    {
        if (!$this->password->hasSameValueAs($supposedPassword)) {
            throw new Exception('invalid password');
        }

        return new UserToken(
            $this->name->value,
            $this->id->value,
            Carbon::now(),
        );
    }
}
