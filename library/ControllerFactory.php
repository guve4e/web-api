<?php
/**
 * Creates controller objects
 * and invokes controller methods
 */
require_once (EXCEPTION_PATH . "/NoSuchControllerException.php");
require_once (EXCEPTION_PATH . "/NoSuchMethodException.php");

class ControllerFactory
{
    /**
     * @var
     * Used to be accessed form Unit-Test
     */
    public $instance;

    /**
     * @var
     * The name of the controller
     * to be invoked
     */
    private $controllerName;

    /**
     * @var
     * The method type used to
     * call the web-api
     */
    private $methodType;

    /**
     * @var
     * Parameter passed to
     * the controller
     */
    private $parameter;

    /**
     * @var
     * Extracted from
     * raw $_SERVER['PATH_INFO']
     */
    private $pathInfo;

    /**
     * @var
     * The path leading to
     * the right controller class
     */
    private $controllerPath;

    /**
     * __construct
     *
     * @access public
     * @param $pathInfo  $_SERVER['PATH_INFO']
     * @throws Exception
     */
    public function __construct($pathInfo)
    {
        // sanitize path info first
        $this->splitPathInfo($pathInfo);

        // get the controller
        $this->retrieveControllerName();

        // get the method
        $this->methodType = $this->retrieveMethodType();

        // get the parameter
        $this->retrieveParameter();

        // build
        $this->build();

    }

    /**
     * Retrieves the Method Type
     * used by the client to
     * make a request to the web-api
     *
     * @return string method name
     * @throws
     */
    private function retrieveMethodType()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") return "get";
        else if ($_SERVER['REQUEST_METHOD'] == "HEAD") return "head";
        else if ($_SERVER['REQUEST_METHOD'] == "POST") return "post";
        else if ($_SERVER['REQUEST_METHOD'] == "PUT") return "put";
        else if ($_SERVER['REQUEST_METHOD'] == "DELETE") return "delete";
        else if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") return "options";
        else if ($_SERVER['REQUEST_METHOD'] == "PATCH") return "patch";
        else throw new NoSuchMethodException($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Extracts the controller name form
     * member array pathInfo
     */
    private function retrieveControllerName()
    {
        $this->controllerName = $this->pathInfo[0];
    }

    /**
     * Extracts the controller parameter form
     * member array pathInfo
     */
    private function retrieveParameter()
    {
        // get the id
        if (isset($this->pathInfo[1])) $this->parameter = $this->pathInfo[1];
    }

    /**
     * It explodes the array given as
     * parameter and validates it.
     * @param $pathInfo: string,
     * @throws ApiException
     */
    private function splitPathInfo($pathInfo)
    {
        // explode and trim
        $this->pathInfo = explode('/', trim($pathInfo,'/'));

        // make sure the $s_path_info no null requests
        if ($this->pathInfo == null) throw new ApiException("PATH_INFO");

        // make sure that request is in the form "/controller/method/id"
        // if $request array has more than 3 elements throw exception
        if (count($this->pathInfo) > 2) throw new ApiException("Wrong Request");
    }

    /**
     * Takes te first char from the
     * controller name and makes it
     * capital, if already not.
     */
    private function sanitizeControllerName()
    {
        // make the first letter of the controller uppercase
        $this->controllerName = ucfirst($this->controllerName);
    }

    /**
     * Constructs the controller path form
     * the parameter given and hard wired path to
     * direcotry. Then it checks if the file exists
     * @throws NoSuchControllerException
     */
    private function constructControllerPath()
    {
        // construct controller
        // controllers' folder + controller-folder + controller-file
        // the reason each controller to be in its own folder is that some
        // controllers have database access and logic in the same folder
        $this->controllerPath = CONTROLLERS_PATH . "/" . $this->controllerName  . "/" . $this->controllerName . '.php';

        // check if controller exists
        if (!file_exists( $this->controllerPath)) throw new NoSuchControllerException($this->controllerName, "ControllerFactory.phpry.php", 143);
    }

    /**
     * Creates an object of the
     * required controller using
     * string substitution
     * (the good stuff that comes
     * with using dynamic languages)
     * @throws ApiException, NoSuchControllerException
     */
    private function build()
    {
        // sanitize controller name first
        $this->sanitizeControllerName();

        //then validate if it exist
        $this->constructControllerPath();

        // if file exists include it
        require_once($this->controllerPath);

        // text substitution
        // @example:
        // $test = new Test();
        $this->instance = new $this->controllerName();

        // authorize the controller
        if (!$this->instance->authorize($this->instance))
        {
            throw new NotAuthorizedException();
        }

        // get the request method
        $method = $this->methodType;

        // invoke method with the right parameter, if provided
        $this->instance->$method($this->parameter);
    }
}