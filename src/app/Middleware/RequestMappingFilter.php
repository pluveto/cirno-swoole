<?php

namespace App\Middleware;

use App\Exception\BadRequestException;
use Hyperf\Utils\Context;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\Di\Annotation\Inject;
/**
 * 该中间件负责将不同格式（Form-data, json...）的请求统一化。
 * @author Pluveto <i@pluvet.com>
 * @copyright 2020 Incolore Team
 */
class RequestMappingFilter extends BaseFilter
{
    
    /**
     * @Inject
     * @var \Hyperf\HttpServer\Contract\RequestInterface
     */
    private $request;

    public function filter(ServerRequestInterface $request): ServerRequestInterface
    {
        $mimeType = $request->getHeaderLine("content-type");
        //echo "ParsedBody: ";
        //var_dump($request->getParsedBody());
        //echo "getQueryParams: ";
        //var_dump($request->getQueryParams());
        //$form = array_merge($request->getParsedBody(), $request->getQueryParams());
        $form = array_merge($this->request->all());
        echo "合并映射的表单：";
        var_dump($form);
        Context::set("app.request.params", (object)$form);
        return $request;
    }
}
