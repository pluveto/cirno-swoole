<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int    $guid 全局编号
 * @property string $name 角色名
 * @property string $title 显示名
 * @property string $description 描述
 * @property int    $parent_guid 上级角色 guid

 * @property \Carbon\Carbon $createdAt 创建时间
 * @property \Carbon\Carbon $updatedAt 更新时间
 */
class SysRole extends AbstractModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_role';
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

}