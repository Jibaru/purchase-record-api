<?php

namespace Core\Auth\Domain\Entities\ValueObjects;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserToken
{
    private const ALGORITHM = 'HS256';

    private string $name;
    private string $subject;
    private Carbon $issuedAt;

    /**
     * @param string $name
     * @param string $subject
     * @param Carbon $issuedAt
     */
    public function __construct(
        string $name,
        string $subject,
        Carbon $issuedAt
    ) {
        $this->name = $name;
        $this->subject = $subject;
        $this->issuedAt = $issuedAt;
    }

    public static function fromJWT(string $jwt): self
    {
        $decoded = JWT::decode($jwt, new Key(config('jwt.secret'), self::ALGORITHM));

        return new self(
            $decoded->name,
            $decoded->sub,
            Carbon::createFromTimestamp($decoded->iat),
        );
    }

    public function toJWT(): string
    {
        return JWT::encode(
            [
                'sub' => $this->subject,
                'iat' => $this->issuedAt->getTimestamp(),
                'exp' => $this->issuedAt->addHours(90)->getTimestamp(),
                'name' => $this->name,
            ],
            config('jwt.secret'),
            self::ALGORITHM
        );
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function toArray(): array
    {
        return [
            'subject' => $this->subject,
            'name' => $this->name,
            'token' => $this->toJWT(),
        ];
    }
}
