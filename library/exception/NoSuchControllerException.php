<?php

require_once ("ApiException.php");

class NoSuchControllerException extends ApiException
{
    private $controller;

    /**
     * NoSuchControllerException constructor.
     *
     * @param $controller_name
     * @param $file
     * @param $line
     */
    public function __construct($controller_name, $file, $line) {
        $this->controller = $controller_name;
        $this->file = $file;
        $this->line = $line;

        parent::__construct($this);
        $this->data = [
            "message" => "There is no such service : " . $controller_name
        ];

        http_response_code(404);
    }

    /**
     * toString magical method
     *
     * @return string
     */
    public function __toString()
    {
        $toString = "There is no such controler (" . $this->controller . ")!\n" .
            "FileManager : " . $this->file . "\n" .
            "Line # " . $this->line . "\n";
        return $toString;
    }
}