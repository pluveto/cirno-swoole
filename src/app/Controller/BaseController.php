<?php


namespace App\Controller;

use App\Type\ResponseWrapper;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class BaseController
{

    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     * src/vendor/hyperf/http-server/src/Request.php
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;


    protected function params(){
        return Context::get("app.request.params");
    }

    /**
     * 成功时返回
     *
     * @param mixed $ret 返回结果
     * @param integer $code 用户状态码，默认和 HTTP 状态码相同
     * @param integer $status HTTP 状态码
     * @return void
     */
    protected function success($ret = null, int $code = null, int $status = 200): Psr7ResponseInterface
    {
        if ($ret == null) {
            $ret = new \stdClass();
        }
        $wrapper = new ResponseWrapper($code ? $code : $status, null, $ret);

        return ((object)$this->response)->withHeader("content-type", "application/json")
        ->withStatus($status)->withBody(new SwooleStream($wrapper->getString()));

    }
    /**
     * 创建成功时返回
     *
     * @param mixed $ret 返回结果
     * @param integer $code 用户状态码，默认和 HTTP 状态码相同
     * @param integer $status HTTP 状态码
     * @return void
     */
    protected function created($ret = null, int $code = null, int $status = 201): Psr7ResponseInterface
    {
        return $this->success($ret, $code, $status);
    }
    /**
     * 创建或修改成功时返回
     *
     * @param mixed $ret 返回结果
     * @param integer $code 用户状态码，默认和 HTTP 状态码相同
     * @param integer $status HTTP 状态码
     * @return void
     */
    protected function updated($ret = null, int $code = null, int $status = 201): Psr7ResponseInterface
    {
        return $this->success($ret, $code, $status);
    }
    /**
     * 删除成功时返回
     *
     * @param mixed $ret 返回结果
     * @param integer $code 用户状态码，默认和 HTTP 状态码相同
     * @param integer $status HTTP 状态码
     * @return void
     */
    protected function deleted($ret = null, int $code = null, int $status = 204): Psr7ResponseInterface
    {
        return $this->success($ret, $code, $status);
    }
}
