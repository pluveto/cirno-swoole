<?php
class NotImplementedException extends BadMethodCallException
{
    public function __construct($message = "Not implemented!")
    {
        parent::__construct($message);
    }
}