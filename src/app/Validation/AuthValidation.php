<?php

namespace App\Validation;

use App\Exception\BadRequestException;
use App\Model\SysUser;
use App\Service\GeneralService;
use App\Service\UserService;
use \Hyperf\Di\Annotation\Inject;

class AuthValidation
{      

    public function validateUUID($uuid){
        $uuid = (trim($uuid));
        if(!preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $uuid)){
            throw new BadRequestException("UUID 无效。");
        }
        return $uuid;
    }
    public function validateUsername(string $in, $new = false): string
    {
        $in = trim(strtolower($in));
        if (mb_strlen($in) < 3) {
            throw new BadRequestException("用户名长度应该大于等于 3 个字符。");
        }
        if (mb_strlen($in) > 32) {
            throw new BadRequestException("用户名长度应该小于等于 32 个字符。");
        }
        if (!preg_match('/^[A-Za-z0-9_\u4e00-\u9fff\uf900-\ufa2d]{3,32}$/', $in)) {
            throw new BadRequestException("用户名不符合要求。只能包含：汉字、数字、英文字母、下划线。");
        }
        
        if($new && (new GeneralService(SysUser::class))->has("username", $in)){
            throw new BadRequestException("用户名已经被其它用户占用！");
        }
        return $in;
    }
    public function validatePassword(string $in, string $username = ""): string
    {
        if (strlen(trim($in)) != strlen($in)) {
            throw new BadRequestException("密码不能包含空格！");
        }
        if (mb_strlen($in) < 8) {
            throw new BadRequestException("密码太简单了，不能少于 8 个字符。", 400, ["current" => $in]);
        }
        if (mb_strlen($in) > 32) {
            throw new BadRequestException("密码长度应该小于等于 32 个字符。", 400,["current" => mb_strlen($in)]);
        }
        if ($in == $username) {
            throw new BadRequestException("密码不能和用户名相同。");
        }
        if (preg_match('/^[\w_-]{8,32}$/', $in)) {
            return $in;
        }
        throw new BadRequestException("密码不符合要求，含有不允许使用的字符。");
    }
}
