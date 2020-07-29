<?php

declare(strict_types=1);

namespace App\Model;

use App\Service\GeneralService;
use Hyperf\DbConnection\Model\Model;

class AbstractModel extends Model
{
    protected $primaryKey = "guid";
    
    public $timestamps = true;

    public $incrementing = false;

    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    public function creating($event)
    {
        if ($this->timestamps) {
            $this->createdAt = time();
            $this->updatedAt = time();
        }
        if (!$this->guid) {
            $this->guid = GeneralService::snowflake()->generate();
        }
    }
    public function updating($event)
    {
        if ($this->timestamps) {
            $this->updatedAt = time();
        }
    }
}
