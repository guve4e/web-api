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
require_once (EXCEPTION_PATH . "/NoSuchControllerException.php");
require_once (EXCEPTION_PATH . "/NoSuchMethodException.php");

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
     * @throws
     */
    private function getMethod()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") return "get";
        else if ($_SERVER['REQUEST_METHOD'] == "POST") return "post";
        else if ($_SERVER['REQUEST_METHOD'] == "PUT") return "put";
        else if ($_SERVER['REQUEST_METHOD'] == "DELETE") return "delete";
        else throw new NoSuchMethodException($_SERVER['REQUEST_METHOD']);
    }

    /**
     * __autoload
     *
     *
     *
     * @param string $class Class name we're trying to load
     * @return void
     * @package api
     */
    private function __autoload($class)
    {
        $file = str_replace('_','/', $class .'.php');

        if( $class == "Authentication") require_once(AUTHENTICATION_PATH . "/" . $file);
    }

    /**
     * Gets data from input string
     * converts it to json and returns it
     * Wrapper over file_get_contents
     *
     * @return mixed
     * @throws ApiException
     */
    private function get_json(){
        //get the data
        $json = file_get_contents("php://input");
        if ($json === false) throw new NoInputStreamException();
        //convert the string of data to an array
        $d = json_decode($json, true);
        return $d;
    }

    /**
     * Main Building method that
     * executes the controller
     * Uses Reflection to make
     * new object and load a controller
     *
     * @throws ApiException
     */
    public function build()
    {
        // make the first letter of the controller uppercase
        $controller = ucfirst($this->controller);

        // construct controller
        // controllers' folder + controller-folder + controller-file
        // the reason each controller to be in its own folder is that some
        // controllers have database access and logic in the same folder
        $controllerFile = CONTROLLERS_PATH . "/" . $controller  . "/" . $controller . '.php';

        // check if controller exists
        if (!file_exists($controllerFile)) throw new NoSuchControllerException($this->controller, "Constructor.php", 143);

        // if file exists include it
        require_once($controllerFile);

        // get the json string from the input stream
        $json_data = $this->get_json();


        //
        // Make an object and call the right method on it
        //

        // make a new class dynamically
        // using Reflection
        // @example
        // $test = new Test($json_data)
        $instance = new $controller($json_data);

        // authorize the controller
        if (!Controller::authorize($instance))
        {
            throw new NotAuthorizedException();
        }

        // get the request method
        $method = $this->method;

        // using Reflection`
        // @example
        // $t->post($id)
        $result = $instance->$method($this->id);
    }
}