<?php

/**
 * NoSuchControllerException
 * Extends the ApiException Class.
 *
 *
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library/exeption
 * @filesource
 */
require_once ("ApiException.php");

class NoSuchControllerException extends ApiException
{
    /**
     * NoSuchControllerException constructor.
     *
     * @param string controller's name
     */
    public function __construct($controller_name) {
        // make sure everything is assigned properly
        parent::__construct("NoSuchController");
        $this->data = [
            "message" => "There is no such service : " . $controller_name
        ];
    }
}