<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $guid 全局编号
 * @property string $username 用户名
 * @property string $email 邮箱
 * @property string $phone 电话
 * @property string $screen_name 昵称
 * @property string $password 密码
 * @property \Carbon\Carbon $createdAt 创建时间
 * @property \Carbon\Carbon $updatedAt 更新时间
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
    protected $casts = ['guid' => 'integer', 'createdAt' => 'datetime', 'updatedAt' => 'datetime'];

    public function role()
    {
        /**
         * select `sys_user`.*,
`sys_user_role`.`user_guid` as `pivot_user_guid`,
`sys_user_role`.`role_guid` as `pivot_role_guid`

from `sys_user` 
	inner join `sys_user_role` 
		on `sys_user`.`guid` = `sys_user_role`.`role_guid` 

where `sys_user_role`.`user_guid` in ('2678392106262786049')
         */ 
        return $this->belongsToMany(SysRole::class, "sys_user_role", "user_guid", "role_guid");
    }
}
