<?php

namespace App\Middleware;

use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
/**
 * 该中间件检查用户是否具有访问路由的权限
 * @author Pluveto <i@pluvet.com>
 * @copyright 2020 Incolore Team
 */
class AuthorizationFilter extends BaseFilter
{
    public function filter(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request;
    }
}

