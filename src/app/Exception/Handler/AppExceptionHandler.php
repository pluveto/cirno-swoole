<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use App\Exception\BaseCirnoHttpException;
use App\Exception\BaseHttpException;
use App\Exception\MethodNotAllowedException;
use App\Type\GeneralResponseWrapper;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof HttpException) {

            $code = $throwable->getStatusCode();
            $result = null;

            if ($throwable instanceof BaseCirnoHttpException) {
                $code = $throwable->getUserCode();
                $result = ["__debug" => $throwable->getDebugData()];
            }

            $wrapper = new GeneralResponseWrapper($code, $throwable->getMessage(), $result);

            // 阻止异常冒泡
            $this->stopPropagation();
        }else{
            $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
            $this->logger->error($throwable->getTraceAsString());
            $wrapper = new GeneralResponseWrapper(500, "Internal server exception.", ["__debug" => $throwable->getTrace()]);
        }
   
        return $response->withHeader("content-type", "application/json")
            ->withStatus(500)->withBody(new SwooleStream($wrapper->getString()));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
