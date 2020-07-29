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

/**
 * Router file.
 * Generated at 2020-07-30 06:28:36 by AutoRouter.
 */

use Hyperf\HttpServer\Router\Router;


/**
 * AuthController
 */
Router::addRoute('POST','/v1/auth/login/{type}', 'App\Controller\AuthController::login');
Router::addRoute('POST','/v1/auth/signup/username', 'App\Controller\AuthController::signUpByUsername');
Router::addRoute('POST','/v1/auth/logout', 'App\Controller\AuthController::logout');
Router::addRoute('GET','/v1/auth/captcha', 'App\Controller\AuthController::getCaptcha');
Router::addRoute('GET','/v1/auth/pubkey', 'App\Controller\AuthController::getPublicKey');

/**
 * IndexController
 */
Router::addRoute('POST','/v1/sum', 'App\Controller\IndexController::sum');

/**
 * SysUserController
 */
Router::addRoute('GET','/v1/admin/sys-users/{guid}', 'App\Controller\Admin\SysUserController::getUser');

