<?php

namespace App\Service;

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

    public function get($type, $value, $withs = [], $excepts = [], $pivot = false)
    {        
        return $this->getWithColumns($type, $value, $withs, ["*"], $excepts, $pivot);
    }
    public function getWithColumns($type, $value, $withs = [], $columns = ["*"], $excepts = [], $pivot = false)
    {
        $userQuery = $this->model::query()
            ->where($type, $value);
        foreach ($withs as $with) {
            $userQuery = $userQuery->with($with);
        }
        $result = $userQuery->first($columns)->toArray();
        foreach ($excepts as $except) {
            unset($result[$except]);
        }

        // Remove pivot field

        if ($pivot) {
            return $result;
        }
        foreach ($withs as $with) {
            // with = "role"
            if (!is_array($result[$with])) {
                continue;
            }
            foreach ($result[$with] as $key => $value) {
                unset($result[$with][$key]["pivot"]);
            }
        }
        return $result;
    }

    public static function snowflake(): IdGenerator
    {
        $container = ApplicationContext::getContainer();
        return $container->get(IdGeneratorInterface::class);
    }
}
