<?php

namespace App\Type;

use App\Service\EncrytpionService;
use Hyperf\Di\Annotation\Inject;

class EncryptedResponseWrapper implements ResponseWrapperInterface
{

    /**
     * 自定义状态码
     *
     * @var int
     */
    protected $code;

    /**
     * 消息。成功时应置 null。
     *
     * @var string
     */
    protected ?string $message;

    /**
     * 响应数据结果
     *
     * @var mixed
     */
    protected $result;


    /**
     * @Inject
     * @var EncrytpionService
     */
    private $encrytpionService;


    public function __construct(int $code, ?string $message, $result)
    {
        $this->code = $code;
        $this->setMessage($message);
        $this->setResult($result);
    }

    public function getArray(): array
    {
        $encryptedResult = $this->encrytpionService->privateEncryptBase64(json_encode($this->result));        
        echo "encryptedResult $encryptedResult\n";
        return  [
            "code" => $this->code,
            "message" => $this->message,
            "result" => $encryptedResult
        ];
    }


    public function getString($format = "json"): ?string
    {
        assert(in_array($format, ["json", /* "xml" */]));
        if ($format == "json") {
            return json_encode($this->getArray());
        }
        return null;
    }


    /**
     * Get 自定义状态码
     *
     * @return  int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get 消息。成功时应置 null。
     *
     * @return  string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get 响应数据结果
     *
     * @return  mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set 自定义状态码
     *
     * @param  int  $code  自定义状态码
     *
     * @return  self
     */
    public function setCode(int $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set 消息。成功时应置 null。
     *
     * @param  string  $message  消息。成功时应置 null。
     *
     * @return  self
     */
    public function setMessage(?string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set 响应数据结果
     *
     * @param  mixed  $result  响应数据结果
     *
     * @return  self
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }
}
