<?php

/**
 * NoInputStreamException
 * Extends the ApiException Class.
 */
require_once ("ApiException.php");

class NoInputStreamException extends ApiException
{
    /**
     * NoInputStreamException constructor.
     *
     * @param string controller's name
     */
    public function __construct() {
        // make sure everything is assigned properly
        parent::__construct("NoInputStreamException");
        $this->data = [
            "message" => "Sorry, This API works only with Input Stream"
        ];
        $this->output();
    }
}