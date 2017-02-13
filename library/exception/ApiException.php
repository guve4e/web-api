<?php

/**
 * ApiException
 * Extends the Exception Class.
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
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // TO_DO

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
    /**
     * Custom string representation of object
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * FOO TO_DO
     */
    public function foo() {
        echo "A custom function for this type of exception\n";
    }
}