<?php

namespace App\Exception;

class BadRequestException extends BaseCirnoHttpException{


    public function __construct($message = "Bad request.", $statusCode = 400, $debugData = null){
        parent::__construct($message, $statusCode, $statusCode, $debugData);
    }
}