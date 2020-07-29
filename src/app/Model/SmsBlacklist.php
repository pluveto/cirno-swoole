<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $guid ID
 * @property string $phone 电话号码
 * @property int $expired_at 过期时间
 * @property string $comment 注释
 */
class SmsBlacklist extends AbstractModel
{
    public $timestamps = false;

    protected $primaryKey = "guid";
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sms_blacklist';
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
    protected $casts = ['guid' => 'integer', 'expired_at' => 'datetime'];
}
