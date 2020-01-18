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

        // make sure everything is assigned properly
        parent::__construct($this);
        $this->data = [
            "message" => "You Are Not Authorized"
        ];
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

    /**
     * toString magical method
     *
     * @return string
     */
    public function __toString()
    {
        $toString = "Not Authorized Access !\n" .
            "IP : " . $_SERVER['SERVER_ADDR'] . "\n";
        return $toString;
    }
}