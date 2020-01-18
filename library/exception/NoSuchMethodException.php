<?php
/**
 * NoSuchControllerException
 * Extends the ApiException Class.
 */
require_once ("ApiException.php");

class NoSuchMethodException extends ApiException
{
    /**
     * NoSuchMethodException constructor.
     *
     * @param string controller's name
     */
    public function __construct($method) {
        // make sure everything is assigned properly
        parent::__construct("NoSuchMethod");
        $this->data = [
            "message" => "Web API does not support " . $method . " method."
        ];
    }
}