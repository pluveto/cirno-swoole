<?php

namespace App\Type;

interface ResponseWrapperInterface
{  

    public function __construct(int $code, ?string $message, $result);

    public function getArray(): array;


    public function getString($format = "json"): ?string;

    /**
     * Get 自定义状态码
     *
     * @return  int
     */
    public function getCode();

    /**
     * Get 消息。成功时应置 null。
     *
     * @return  string
     */
    public function getMessage();

    /**
     * Get 响应数据结果
     *
     * @return  mixed
     */
    public function getResult();

    /**
     * Set 自定义状态码
     *
     * @param  int  $code  自定义状态码
     *
     * @return  self
     */
    public function setCode(int $code);

    /**
     * Set 消息。成功时应置 null。
     *
     * @param  string  $message  消息。成功时应置 null。
     *
     * @return  self
     */
    public function setMessage(?string $message);

    /**
     * Set 响应数据结果
     *
     * @param  mixed  $result  响应数据结果
     *
     * @return  self
     */
    public function setResult($result);

}
