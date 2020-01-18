<?php
 /**
  * MethodNotImplementedException
  * Extends the ApiException Class.
  */
require_once ("ApiException.php");

class MethodNotImplementedException extends ApiException
{
    /**
     * Name of Method
     * @var string
     */
    private $method;

    /**
     * MethodNotImplementedException constructor.
     * @param string $method
     * @param int $file
     * @param Exception $line
     */
    public function __construct($method, $file, $line) {
        $this->method = $method;
        $this->line = $line;
        $this->file = $file;

        // make sure everything is assigned properly
        parent::__construct($this);
        $this->data = [
            "message" => "This method (" . $method . ") is not implemented!"
        ];

        http_response_code(500);
    }

    /**
     * toString magical method
     *
     * @return string
     */
    public function __toString()
    {
        $toString = "This method (" . $this->method . ") is not implemented!\n" .
            "FileManager : " . $this->file . "\n" .
            "Line # " . $this->line . "\n";
        return $toString;
    }
}