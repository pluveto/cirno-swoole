<?php

namespace App\Exception;

use Hyperf\HttpMessage\Exception\HttpException;

class BaseCirnoHttpException extends HttpException
{

    protected int $userCode;
    protected $debugData;

    public function __construct(string $message, int $userCode = 400, int $statusCode = 400, $debugData = null)
    {
        parent::__construct($statusCode, $message);
        $this->userCode = $userCode;
        $this->debugData = $debugData;
    }

    /**
     * Get the value of userCode
     */
    public function getUserCode()
    {
        return $this->userCode;
    }

    /**
     * Get the value of debugData
     */
    public function getDebugData()
    {
        return $this->debugData;
    }
}
