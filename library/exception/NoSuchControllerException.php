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
    private $controller;

    /**
     * NoSuchControllerException constructor.
     *
     * @param string controller's name
     */
    public function __construct($controller_name, $file, $line) {
        $this->controller = $controller_name;
        $this->file = $file;
        $this->line = $line;
        // make sure everything is assigned properly
        parent::__construct($this);
        $this->data = [
            "message" => "There is no such service : " . $controller_name
        ];
    }

    /**
     * toString magical method
     *
     * @return string
     */
    public function __toString()
    {
        $toString = "There is no such controler (" . $this->controller . ")!\n" .
            "File : " . $this->file . "\n" .
            "Line # " . $this->line . "\n";
        return $toString;
    }
}