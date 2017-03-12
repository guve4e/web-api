<?php

/**
 * NotAuthorizedException
 * Extends the ApiException Class.
 * Handles Users that are not
 * Authorized
 *
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library/exeption
 * @filesource
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
        parent::__construct("Not Authorized");
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
        parent::output(); // call parernt first
        header(VIEW_PATH . "/authentication.php");
        die();
    }
}