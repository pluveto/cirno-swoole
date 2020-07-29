<?php

namespace App\Service;

use App\Model\SysUser;
use Hyperf\Config\Annotation\Value;
use Hyperf\Database\Model\Model;
use Hyperf\Di\Annotation\Inject;

class SysUserService extends AbstractService
{
    /**
     * @Inject
     * @var AuthService
     */
    private $authService;
    public function signUpUserWith($type, $value, $password): SysUser
    {
        assert(in_array($type, ["username", "phone", "email"]));
        $user = new SysUser();
        $user->$type = $value;
        $user->password = $this->authService->hashPassword($password);
        $user->screenName = $type == "username" ? $value : "未设置昵称";
        $user->save();
        return $user;
    }
    public function loginUserWith($type, $value, $password): ?SysUser
    {
        assert(in_array($type, ["username", "phone", "email"]));
        $user =  SysUser::query()
            ->where($type, $value)
            ->where("password", $this->authService->hashPassword($password))
            ->first();
        return $user;
    }
}
