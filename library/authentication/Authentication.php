<?php

include(LIBRARY_PATH . "/Controller.php");
/**
 * Authentication
 *
 * Base class of authentication classes. The controller will check to make
 * sure your controller is an instance of Authentication class.
 *
 * @see Module
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
    public function output($data)
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
     * GET
     */
    public function get($id)
    {
        $controller_name = get_class($this);
        Logger::logMsg($controller_name,"GET");
    }

    /**
     * POST
     */
    public function post($id)
    {
        $controller_name = get_class($this);
        Logger::logMsg($controller_name,"POST");

        //get the incoming data
        $this->json_data = $this->getJsonData();
    }

    /**
     * PUT
     */
    public function put($id)
    {
        $controller_name = get_class($this);
        Logger::logMsg($controller_name,"PUT");

        //get the incoming data
        $this->json_data = $this->getJsonData();
    }

    /**
     * DELETE
     * TODO Not sure if DELETE needs input stream data
     */
    public function delete($id)
    {
        $controller_name = get_class($this);
        Logger::logMsg($controller_name,"DELETE");

        //get the incoming data
        $this->json_data = $this->getJsonData();
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

