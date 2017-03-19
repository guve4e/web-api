<?php

/**
 * NoInputStreamException
 * Extends the ApiException Class.
 *
 *
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library/exeption
 * @filesource
 */
require_once ("ApiException.php");

class NoInputStreamException extends ApiException
{
    /**
     * NoSuchControllerException constructor.
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