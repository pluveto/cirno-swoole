<?php

namespace App\Middleware;

use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 该中间件检查用户是否登录，若登录则初始化当前用户
 * @author Pluveto <i@pluvet.com>
 * @copyright 2020 Incolore Team
 */
class AuthenticationFilter extends BaseFilter
{
    public function filter(ServerRequestInterface $request): ServerRequestInterface
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (property_exists($request, "subject") && $request->subject) return $request;
        if (!$authHeader) return $request;
        if (!str_starts_with(strtolower($authHeader), "bearer")) return $request;
        $token = substr($authHeader, strlen("bearer "));
        
        echo "\nToken=$token\n";        
        return $request;
    }
}
