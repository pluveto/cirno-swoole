<?php


namespace App\Controller;

use App\Controller\BaseController;
use Hyperf\Di\Annotation\Inject;
use App\Exception\BadRequestException;
use App\Middleware\Auth\ResponseEncryptionMiddleware;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use App\Service\AuthService;
use App\Service\SysUserService;
use App\Validation\AuthValidation;
use Hyperf\Utils\ApplicationContext;
use Pluveto\Swoole\Captcha\CaptchaBuilder;

class AuthController extends BaseController
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
     * @api {post} /auth/login/{type} 登录
     * @apiName Login
     * @apiGroup auth
     * @apiVersion  1.0.0
     * @apiPermission none
     * @apiEncrypted
     *
     * @apiParam  {String{3..}} subject 用户名/邮箱/手机
     * @apiParam  {String{6..}} password 密码
     * 
     */
    public function login($type)
    {
        
        $req = $this->params();

        if (!in_array($type, ["username", "phone", "email"])) {
            throw new BadRequestException(__("Incorrect login type."));
        }

        $subject = $req->subject;
        $password = $req->password;

        if ($type == "username") $subject = $this->authValidation->validateUsername($subject);
        $password = $this->authValidation->validatePassword($password, $subject);

        $user = $this->sysUserService->loginUserWith($type, $subject, $password);

        if (null == $user) {
            throw new BadRequestException(__("Incorrect username or password"));
        }

        $token = $this->authService->authorize($user->guid);

        return $this->successWithEncryption([
            "token" => $token->toString()
        ], null, 201);
    }

    /**
     *
     * @api {post} /auth/signup/username 通过用户名注册
     * @apiName SignUpByUsername
     * @apiGroup auth
     * @apiVersion  1.0.0
     * @apiPermission none
     * @apiEncrypted     
     * @apiParam  {String{3..}} username 用户名
     * @apiParam  {String{6..}} password 密码
     * 
     */
    public function signUpByUsername()
    {
        $req = $this->params();

        $username = $req->username;
        $password = $req->password;

        $username = $this->authValidation->validateUsername($username, true);
        $password = $this->authValidation->validatePassword($password, $username);

        $user = $this->sysUserService->signUpUserWith("username", $username, $password);

        $token = $this->authService->authorize($user->guid);

        return $this->successWithEncryption([
            "token" => $token->toString()
        ], null, 201);
    }

    /**
     *
     * @api {post} /auth/logout 注销登录
     * @apiName Logout
     * @apiGroup auth
     * @apiVersion  1.0.0
     * @apiPermission none
     */
    public function logout()
    {
        return $this->success();
    }

    /**
     * @api {get} /auth/captcha 获取验证码
     * @apiName GetCaptcha
     * @apiGroup auth
     * @apiVersion  1.0.0
     * @apiPermission none
     *
     * @apiParam  {number{32-320}} [width=180] 宽度
     * @apiParam  {number{32-320}} [height=64] 高度
     * @apiParam  {boolean} [raw=true]
     * @apiParam  {string} [uuid=""] UUID
     */

    public function getCaptcha()
    {
        $captha = new CaptchaBuilder();
        $req = $this->params();
        if (!$req->uuid) {
            $uuid = uuid();
        } else {
            $uuid = $this->authValidation->validateUUID($req->uuid);
        }

        $captha->initialize([
            'width' => $req->width,     // 宽度
            'height' => $req->height,     // 高度
            'line' => false,    // 直线
            'curve' => true,    // 曲线
            'noise' => 1,       // 噪点背景
            'fonts' => []       // 字体
        ]);
        $captha->create();
        $text = $captha->getText();
        $redis = ApplicationContext::getContainer()->get(\Hyperf\Redis\Redis::class);
        $redis->set('captcha_' . str_replace("-", "", $uuid), $text, 60);
        var_dump($req);
        if ($req->raw) {
            return $captha->output($this->response->raw(""), 1);
        } else {
            return $this->success(
                [
                    "uuid" => $uuid,
                    "expiredAt" => time() + 60,
                    "content" => $captha->getBase64(1)
                ]
            );
        }
    }
    /**
     *
     * @api        {get} /auth/pubkey 获取公钥
     * @apiName    GetPublicKey
     * @apiGroup   auth
     * @apiParam   {boolean} [raw=false] 是否输出纯文本
     * @apiVersion 1.0.0
     */
    public function getPublicKey()
    {
        $publicKey = config("keys.public");
        echo $this->params()->raw;
        if ($this->params()->raw === true) {
            return $this->response->raw($publicKey);
        }
        $version = config("keys.version");
        return $this->success(
            [
                "key" => $publicKey,
                "version" => $version
            ]
        );
    }
}
