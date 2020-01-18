<?php
 /**
  * FileException
  * Extends the ApiException Class.
  */
require_once ("ApiException.php");

class FileException extends ApiException
{
    public function __construct($msg) {
        parent::__construct($msg);

        http_response_code(500);
    }
}