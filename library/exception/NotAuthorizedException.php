<?php

/**
 * NotAuthorizedException
 * Extends the ApiException Class.
 * Handles Users that are not
 * Authorized
 */
require_once ("ApiException.php");

class NotAuthorizedException extends ApiException
{
    /**
     * NoSuchControllerException constructor.
     *
     * @param string controller's name
     */
    public function __construct() {
        parent::__construct($this);
        $this->data = [
            "message" => "You Are Not Authorized"
        ];

        http_response_code(401);
    }

    /**
     * Output
     * @override
     */
    public function output()
    {
        parent::output();
        die();
    }
}