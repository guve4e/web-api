<?php

include(LIBRARY_PATH . "/Controller.php");
/**
 * Authentication class.
 * This class sends data back to front end.
 * It provides each controller with logging ability.
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library
 */
abstract class Authentication extends Controller
{

    /**
     * __construct
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }// end

    /**
     *
     * @abstract
     * @param $var
     * @return mixed
     */
    public abstract function authenticate($var);

    /**
     * output
     *
     * This function takes an array as
     * argument encodes it as json string
     * and prints the result on the string
     * @param $data
     * @throws ApiException
     */
    protected function output($data)
    {
        // set options
        $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

        // try to encode the string
        $json_string = json_encode($data, $options);
        // check for ptoper encoding
        if( $json_string === false ) throw new ApiException( json_last_error() );
        // print on screen
        echo($json_string);
    }

    /**
     * All overridden functions
     * get/post/put/delete
     * use this method to set their log
     *
     * @param $method_name
     * @param $id
     * @param $msg
     */
    private function setLog($method_name, $id = "No Id", $msg = "No Message")
    {
        $controller_name = get_class($this);
        $toString = "Method  : " . $method_name . "\n" .
                    "Id      : " . $id . "\n" .
                    "Message : " . $msg  . "\n";
        Logger::logMsg($controller_name, $toString);
    }

    /**
     * GET
     * @param $id
     * @param string $msg
     * @return void
     */
    public function get($id = "No Id", $msg = "No Message")
    {
        $this->setLog("GET", $id, $msg);
    }

    /**
     * POST
     * @param $id
     * @param string $msg
     * @return void
     */
    public function post($id = "No Id", $msg = "No Message")
    {
        $this->setLog("POST", $id, $msg);
    }

    /**
     * PUT
     * @param $id
     * @param string $msg
     * @return void
     */
    public function put($id = "No Id", $msg = "No Message")
    {
        $this->setLog("PUT", $id, $msg);

    }

    /**
     * DELETE
     *
     * @param $id
     * @param string $msg
     * @return void
     */
    public function delete($id = "No Id", $msg = "No Message")
    {
        $this->setLog("DELETE", $id, $msg);
    }

    /**
     * __destruct
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        parent::__destruct();
    }// end

}// end class


