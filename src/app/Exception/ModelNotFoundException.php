<?php

namespace App\Exception;

class ModelNotFoundException extends BaseCirnoHttpException{
    public function __construct($message = "Model not found.", $statusCode = 404, $debugData = null){
        parent::__construct($message, $statusCode,$statusCode, $debugData);
    }
}