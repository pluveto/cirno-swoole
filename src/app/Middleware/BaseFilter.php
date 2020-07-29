<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 
 * @author Pluveto <i@pluvet.com>
 * @copyright 2020 Incolore Team
 */
class BaseFilter  implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $classFullName =  get_class($this);
        echo "经过 \e[1;36m$classFullName\e[0m\n";
        $afterFilter = $this->filter($request);
        \Hyperf\Utils\Context::set(ServerRequestInterface::class, $afterFilter);
        return $handler->handle($request);
    }
    public function filter(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request;
    }
}
