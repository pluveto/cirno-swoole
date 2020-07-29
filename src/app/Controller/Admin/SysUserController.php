<?php


namespace App\Controller\Admin;

use App\Controller\BaseController;
use Hyperf\Di\Annotation\Inject;
use App\Exception\BadRequestException;
use App\Exception\ModelNotFoundException;
use App\Middleware\Auth\ResponseEncryptionMiddleware;
use App\Model\SysUser;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use App\Service\AuthService;
use App\Service\GeneralService;
use App\Service\SysUserService;
use App\Validation\AuthValidation;
use http\Client\Curl\User;
use Hyperf\Utils\ApplicationContext;
use Pluveto\Swoole\Captcha\CaptchaBuilder;

class SysUserController extends BaseController
{

    /**
     * authValidation
     *
     * @Inject
     * @var AuthValidation
     *
     */
    private $authValidation;


    /**
     * @Inject
     * @var SysUserService
     */
    private $sysUserService;

    /**
     * @Inject
     * @var AuthService
     */
    private $authService;


    public function index()
    {
        throw new BadRequestException();
    }

    /**
     *
     * @api {get} /admin/sys-users/{guid} 获取用户信息
     * @apiName getUser
     * @apiGroup AdminUser
     * @apiVersion  1.0.0
     * @apiPermission none
     * 
     * @apiParam {string} [fields=""]
     */
    public function getUser($guid)
    {
        $params = $this->params();

        $withAllowed = ["role"];
        $columnsAllowd = ["guid", "username", "phone", "screenName", "password", "createdAt", "updatedAt"];
        // 获取用户所需的字段，如果是空就当作全部获取
        if (empty($params->fields)) {
            $fields =  array_merge($withAllowed, $columnsAllowd);
        } else {            
            $fields = explode(",", $params->fields);
            if (!all_in_array($fields, array_merge($withAllowed, $columnsAllowd))) {
                throw new BadRequestException(trans("Bad `field` arguments!"), 400, $fields);
            }
        }

        [$withs, $columns] = array_cluster($fields, [$withAllowed, $columnsAllowd]);

        $user = (new GeneralService(SysUser::class))->getWithColumns("guid", $guid, $withs, $columns);

        if (!$user) {
            throw new ModelNotFoundException();
        }

        return $this->success($user);
    }
}
