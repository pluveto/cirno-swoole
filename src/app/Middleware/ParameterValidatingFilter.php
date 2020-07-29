<?php

namespace App\Middleware;

use App\AppLogger;
use App\Exception\BadRequestException;
use App\Service\EncrytpionService;
use Hyperf\Config\Annotation\Value;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 该中间件检查请求参数是否合法。
 * 本函数将会给 $request 对象注入 data 属性，存放请求的参数内容，并对允许默认值的赋予默认值。
 * @copyright 2020 Incolore Team
 */
class ParameterValidatingFilter extends BaseFilter
{
    /**
     * @Value("rules.rules")
     */
    private $allRules;

    /**
     * @Inject
     * @var EncrytpionService
     */
    private $encrytpionService;

    public function filter(ServerRequestInterface $request): ServerRequestInterface
    {
        $target =  Context::get("app.request.target");
        echo "target: $target \n";
        $ruleMap = $this->allRules[$target];

        // 对于加密请求，将请求体的 crendentials 参数重新解码为真实的请求体。
        $form = Context::get("app.request.params");
        if (array_get_if_key_exists($ruleMap, '__encrypted', false)) {
            Context::set("app.request.encrypted", true);
            if (!property_exists($form, "crendentials")) {
                throw new BadRequestException("Missing crendentials.");
            }
            $form = $this->encrytpionService->decryptBase64($form->crendentials);
            if (!$form) {
                throw new BadRequestException("Invalid crendentails.");
            }
        }
        // 检查后返回用户提交的表单，此表单经过了过滤        
        Context::set("app.request.params", $this->paramCheck($form, $ruleMap));
        return $request;
    }

    /**
     * 参数基础静态检查, 检查输入的参数是否缺失, 长度是否在范围内等等.
     * 如果遇到任何问题, 就抛出相应错误
     * 
     * TODO: 上传文件检查
     * 
     * @param string path 用户请求的路由 
     * @param array ruleMap 规则
     * @return object
     */
    private function paramCheck($form,  array $ruleMap): object
    {
        echo "待检查的表单如下";
        var_dump($form);
        foreach ($ruleMap as $param => $rules) {


            if (str_starts_with($param, "__")) {
                continue;
            }
            /**
             * 首先可基本分为四种情况:
             * 
             *  1. 参数必要, 表单有 -> 继续处理
             *  2. 参数非必要, 表单有 -> 继续处理
             *  3. 参数必要, 表单无 -> 跳过
             *  3. 参数非必要, 表单无 -> 跳过
             * 
             *  注: 不考虑参数必要但有默认值这种情况. 有默认值就当作参数非必要处理
             * 
             * 因此下面先写跳过的两种情况
             */

            // 检查是否缺少必要参数
            if (array_get_if_key_exists($rules, "required", false)) {
                if (!property_exists($form, $param)) {
                    throw new BadRequestException("缺少必要参数：" . $rules['description'], 400, $param);
                }
            }

            // 如果参数是非必要参数, 且提交的表单没有这个参数, 那么就跳过
            echo "是否设定了参数$param: " . json_encode(isset($form->$param)) . "\n";

            if (!array_get_if_key_exists($rules, "required", false) && (isset($form->$param) === false)) {
                // 如果有默认值就赋予一个默认值
                if (($default = array_get_if_key_exists($rules, "default", null)) !== null) {
                    $form->$param = $default;
                    echo "$param 有默认值 $default\n";
                } else {
                    echo "$param 没有默认值\n";
                }

                continue;
            }
            /**
             * --> options 检查
             */
            $options = array_get_if_key_exists($rules, "options", []);
            if (count($options)) {
                if (in_array($form->$param, $options)) continue;
                throw new BadRequestException($rules['description'] . "值无效, 应在枚举范围内. ", 400, [
                    "given" => $form->$param,
                    "allow" => $options
                ]);
            }
            /**
             * --> type 检查
             */
            if (!($type = array_get_if_key_exists($rules, "type", false))) {
                echo "无类型";
                continue;
            }
            switch ($type) {
                case 'integer':
                    if (!is_numeric($form->$param)) {
                        throw new BadRequestException($rules['description'] . "应当为整数. ");
                    }
                    $number = intval($form->$param);
                    $min = array_get_if_key_exists($rules, "min", 0);
                    $max = array_get_if_key_exists($rules, "max", 0);
                    if ($min && $number < $min) {
                        throw new BadRequestException($rules['description'] . "应当大于 $min. ");
                    }
                    if ($max && $number > $max) {
                        throw new BadRequestException($rules['description'] . "应当小于 $max. ");
                    }
                    // 数字类型的参数会转换为
                    $form->$param = $number;
                    break;
                case 'string':
                    $min = array_get_if_key_exists($rules, "min", 0);
                    $max = array_get_if_key_exists($rules, "max", 0);
                    if (!is_string($form->$param)) {
                        throw new BadRequestException($rules['description'] . "应当为字符串. ");
                    }
                    $strLength = mb_strlen($form->$param);
                    if ($min && $strLength < $min) {
                        throw new BadRequestException($rules['description'] . "长度应当大于等于 $min. ");
                    }
                    if ($max && $strLength > $max) {
                        throw new BadRequestException($rules['description'] . "长度应当小于等于 $max. ");
                    }
                    break;
                case 'boolean':
                    if (strtolower($form->$param) === "true") {
                        $form->$param = true;
                    }
                    if (strtolower($form->$param) === "false") {
                        $form->$param = false;
                    }
                    if (!is_bool($form->$param)) {
                        throw new BadRequestException($rules['description'] . "应当为布尔值 (true/false). ");
                    }
                    $form->$param =  $form->$param;
                    break;
                case 'array':
                    if (!is_array($form->$param)) {
                        throw new BadRequestException($rules['description'] . "应当为数组. ");
                    }
                    break;
                default:
                    break;
            }
        }
        return $form;
    }
}
