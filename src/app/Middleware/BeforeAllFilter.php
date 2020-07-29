<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\ActionNotFoundException;
use App\Exception\MethodNotAllowedException;
use FastRoute\Dispatcher;
use Hyperf\HttpServer\Router\Dispatched;
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
class BeforeAllFilter  extends BaseFilter
{
    public function filter(ServerRequestInterface $request): ServerRequestInterface
    {

        /** @var Dispatched $dispatched */
        $dispatched = $request->getAttribute(Dispatched::class);
        switch ($dispatched->status) {
            case Dispatcher::NOT_FOUND:
                throw new ActionNotFoundException();
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException();
        }
        $target = $dispatched->handler->route;
        echo "Handler route: ". $target  . "\n";
        Context::set("app.request.target", $target);
        return $request;
    }
}
