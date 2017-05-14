<?php

/**
 * ApiException
 * Extends the Exception Class.
 * Is base for ApiExceptions
 *
 * Provides ability to log an exception.
 * Change So you can document every exception
 * with code and special behaviour.
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library/exeption
 * @filesource
 */
class ApiException extends Exception
{
    /**
     * Object to be send
     * to the client
     * @var mixed
     */
    protected $data = null;

    /**
     *
     * @var int
     */
    protected $option_bits =  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;


    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    /**
     * Send message to client
     */
    public function output() {
        // Log just message for now
        Logger::logException($this->message);
        // send to client
        echo( json_encode($this->data, $this->option_bits ));
    }
}