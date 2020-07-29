<?php

namespace App\Type;

use Firebase\JWT\JWT;
use Hyperf\Config\Annotation\Value;

class JwtToken implements TokenInterface
{
    protected int $guid;

    protected int $expiredAt;

    public function __construct($guid, $expiredAt)
    {
        $this->guid = $guid;
        $this->expiredAt = $expiredAt;
    }



    public function toString(): string
    {
        $payload = [
            "guid" => $this->guid,
            "expiredAt" => $this->expiredAt
        ];
        return JWT::encode($payload,  config("keys.jwt"));
    }

    public static function create($guid, int $timeout): TokenInterface
    {
        return (new self($guid, time() + $timeout));
    }

    public static function parse(string $encoded): TokenInterface
    {
        $payload = JWT::decode($encoded, config("keys.jwt"));
        return new self($payload->guid, $payload->expiredAt);
    }

    /**
     * Get the value of expiredAt
     */
    public function getExpiredAt(): int
    {
        return $this->expiredAt;
    }

    /**
     * Get the value of guid
     */
    public function getGuid()
    {
        return $this->guid;
    }
}
