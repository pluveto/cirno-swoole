<?php

namespace App\Service;

use App\Model\SysUser;
use Hyperf\Config\Annotation\Value;
use Hyperf\Database\Model\Model;
use Hyperf\Snowflake\IdGenerator;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\ApplicationContext;

class GeneralService extends AbstractService
{
    /**
     * Model::class
     *
     * @var Model
     */
    protected $model;

    /**
     * Pass XX::class as param!
     *
     * @param Class $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }


    public function has(string $key, $value): bool
    {
        return $this->model::query()->where($key, $value)->count() >= 1;
    }

    public function get(string $key, $value)
    {
        return $this->model::query()->where($key, $value)->first();
    }

    public static function snowflake(): IdGenerator
    {
        $container = ApplicationContext::getContainer();
        return $container->get(IdGeneratorInterface::class);
    }
}
