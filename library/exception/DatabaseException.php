<?php

/**
 * DatabaseException
 * Extends the ApiException Class.
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

        http_response_code(500);
    }
}