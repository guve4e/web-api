<?php
/**
 * Module
 *
 * The base module class. Authentication and NoAuthentication will
 * extend this class.
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library
 */

class Constructor
{
    /**
     * @var
     */
    private $controller;

    /**
     * @var
     */
    private $method;

    /**
     * @var
     */
    private $id;

    /**
     * @var
     */
    private $action;

    /**
     * @var
     */
    private $data;


    /**
     * __construct
     *
     * @access public
     * @param $s_path_info  $_SERVER['PATH_INFO']
     * @throws Exception
     */
    public function __construct($s_path_info)
    {
        // explode and trim
        $request = explode('/', trim($s_path_info,'/'));

        // make sure the $s_path_info no null requests
        if ($request == null) throw new ApiException("PATH_INFO");

        // make sure that request is in the form "/controller/method/id"
        // if $request array has more than 3 elements throw exception
        if (count($request) > 2) throw new ApiException("Wrong Request");

        // get the controller
        $this->controller = $request[0];

        // get the method
         $this->method = $this->getMethod();

        // get the id
        if (isset($request[1])) $this->id = $request[1];

        // build
        $this->build();

    }// end constructor

    /**
     * Depends on the Request method
     *
     * @return string method name
     */
    private function getMethod()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") return "get";
        else if ($_SERVER['REQUEST_METHOD'] == "POST") return "post";
        else if ($_SERVER['REQUEST_METHOD'] == "PUT") return "put";
        else if ($_SERVER['REQUEST_METHOD'] == "DELETE") return "delete";
    }

    /**
     * __autoload
     *
     * Autoload is ran by PHP when it can't find a class it is trying to load.
     * By naming our classes intelligently we should be able to load most classes
     * dynamically.
     *
     * @param string $class Class name we're trying to load
     * @return void
     * @package api
     */
    private function __autoload($class)
    {
        $file = str_replace('_','/', $class .'.php');

        if( $class == "Authentication")
            require_once(AUTHENTICATION_PATH . "/" . $file);
    }

    /**
     * Main Building method that
     * executes the controller
     *
     * @throws Exception
     */
    public function build()
    {
        $controllerFile = CONTROLLERS_PATH . "/" .$this->controller . '.php';

        // check if controller exists
        if (file_exists($controllerFile)) {
            // include it
            require_once($controllerFile);

            // handle it here
            try
            {
                // make a new class dynamically
                // using Reflection
                // @example
                // $t = new Test()
                $instance = new $this->controller();

                // authorize the controller
                if (!Controller::authorize($instance))
                {
                    throw new Exception("Requested controller is not a valid!");
                }

                $method = $this->method;

                // using Reflection
                // @example
                // $t->post($id)
                $result = $instance->$method($this->id);

            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }

        }
        else
        {
            throw new Exception("Controller " . $controllerFile . " does NOT exist!");
        }
    }
}