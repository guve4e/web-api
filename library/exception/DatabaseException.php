<?php

/**
 * DatabaseException
 * Extends the ApiException Class.
 *
 *
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library/exeption
 */
require_once ("ApiException.php");

class DatabaseException extends ApiException
{
    /**
     * DatabaseException constructor.
     */
    public function __construct($msg) {
        // make sure everything is assigned properly
        parent::__construct($this);
        $this->data = [
            "message" => "Unsuccessful connection to database, " . $msg,
        ];
    }
}