<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $guid 全局编号
 * @property string $username 用户名
 * @property string $email 邮箱
 * @property string $phone 电话
 * @property string $screen_name 昵称
 * @property string $password 密码
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 */
class SysUser extends AbstractModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['guid' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}