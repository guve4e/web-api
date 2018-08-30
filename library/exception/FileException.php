<?php
 /**
  * FileException
  * Extends the ApiException Class.
  *
  *
  * TODO:
  * @license http://www.opensource.org/licenses/gpl-license.php
  * @package library/exeption
  * @filesource
  */
require_once ("ApiException.php");

class FileException extends ApiException
{
    public function __construct($msg) {
        // make sure everything is assigned properly
        parent::__construct($msg);
    }
}