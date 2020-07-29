<?php

namespace App\Service;

use App\Type\JwtToken;
use App\Type\TokenInterface;
use Hyperf\Config\Annotation\Value;
use Hyperf\Framework\Exception\NotImplementedException;

class AuthService
{
    public static function hashPassword(string $password): string
    {
        $salt = config("keys.salt", "");
        $password = base64_encode(hash("SHA256", $salt . $password, PASSWORD_DEFAULT));
        return $password;
    }
    public static function authorize(int $guid, string $type = "jwt"): TokenInterface
    {
        if ($type == "jwt") {
            return JwtToken::create($guid, config("keys.token_timeout", 60 * 60));
        }
        return new NotImplementedException();
    }
}
