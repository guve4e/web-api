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

    protected $time;

    /**
     *
     * @var int
     */
    protected $option_bits =  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;


    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);

        // get time and date
        $dateTime = new DateTime('2016-03-11 11:00:00');

        $this->data = [
            "message" => $message,
            "time" => $dateTime->getTimestamp()
        ];
    }

    /**
     * ControllerFactory
     * @param $ex
     */
    public function __construct1($ex)
    {   // give the exception ot the parent
        parent::__construct($ex);
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