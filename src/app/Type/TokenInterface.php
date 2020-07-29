<?php

namespace App\Type;

interface TokenInterface
{
    public function toString(): string;
    public function getGuid();
    public function getExpiredAt(): int;
    public static function create($guid, int $timeout): TokenInterface;
    public static function parse(string $encoded): TokenInterface;
}
