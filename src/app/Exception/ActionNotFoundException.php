<?php

namespace App\Exception;

class ActionNotFoundException extends BaseCirnoHttpException{
    public function __construct($message = "Action not found.", $statusCode = 404, $debugData = null){
        parent::__construct($message, $statusCode,$statusCode, $debugData);
    }
}