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

namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;

class IndexController extends BaseController
{
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }

    /**
     * 
     * @api {POST} /sum Sum
     * @apiName Sum
     * @apiGroup index
     * @apiVersion  1.0.0
     * 
     * @apiParam {number} a 数字 a
     * @apiParam {number} b 数字 b
     * 
     */
    public function sum()
    {
        $req = $this->params();

        $a = $req->a;
        $b = $req->b;

        return $this->success($a + $b);
    }
}
