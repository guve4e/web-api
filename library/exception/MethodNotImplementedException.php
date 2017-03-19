<?php
 /**
  * MethodNotImplementedException
  * Extends the ApiException Class.
  *
  *
  *
  * @license http://www.opensource.org/licenses/gpl-license.php
  * @package library/exeption
  * @filesource
  */
require_once ("ApiException.php");

class MethodNotImplementedException extends ApiException
{
    /**
     * MethodNotImplementedException constructor.
     *
     * @param string method's name
     */
    public function __construct($method) {
        // make sure everything is assigned properly
        parent::__construct("NoSuchController");
        $this->data = [
            "message" => "This " . $method . " is not implemented"
        ];
    }
}