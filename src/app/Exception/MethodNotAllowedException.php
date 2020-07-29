<?php

namespace App\Exception;

class MethodNotAllowedException extends BaseCirnoHttpException{
    public function __construct($message = "Method Not Allowed.", $statusCode = 405, $debugData = null){
        parent::__construct($message, $statusCode,$statusCode, $debugData);
    }
}