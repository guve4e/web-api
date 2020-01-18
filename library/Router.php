<?php
/**
 * Creates controller objects
 * and invokes controller methods
 */
require_once (EXCEPTION_PATH . "/NoSuchControllerException.php");
require_once (EXCEPTION_PATH . "/NoSuchMethodException.php");
require_once (LIBRARY_PATH . "/Splicer.php");
require_once (HTTP_PATH . "/RestCall.php");

class Router
{
    /**
     * @var
     *
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
     * @var array
     * If request has more
     * than one parameters,
     * will be stored in array;
     */
    private $parameters;

    /**
     * @var
     * The path leading to
     * the right controller class
     */
    private $controllerPath;

    /**
     * @var FileManager obj
     */
    private $fileManager;

    /**
     * Router Constructor
     *
     * @access public
     * @param FileManager $fileManager
     * @param string $pathInfo $_SERVER['PATH_INFO']
     * @throws Exception
     * @throws ApiException
     * @throws NoSuchControllerException
     * @throws NoSuchMethodException
     */
    public function __construct(FileManager $fileManager, string $pathInfo)
    {
        if (!isset($pathInfo) || empty($pathInfo))
            throw new NoSuchControllerException("Null Controller Name", "Router", 66);

        $this->fileManager = $fileManager;

        // retrieve the controller name
        $splicer = new Splicer($pathInfo);
        $this->controllerName = $splicer->getControllerName();

        // retrieve parameters
        $this->parameters = $splicer->getParameters();

        // get the method
        $this->methodType = $this->retrieveMethodType();

        // build
        $this->route();
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
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if ($requestMethod == "GET") return "get";
        else if ($requestMethod == "HEAD") return "head";
        else if ($requestMethod == "POST") return "post";
        else if ($requestMethod == "PUT") return "put";
        else if ($requestMethod == "DELETE") return "delete";
        else if ($requestMethod == "OPTIONS") return "options";
        else if ($requestMethod == "PATCH") return "patch";
        else throw new NoSuchMethodException($requestMethod);
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
     * directory. Then it checks if the file exists.
     * @throws NoSuchControllerException
     */
    private function constructControllerPath()
    {
        // construct controller
        // controllers' directory + controller-directory + controller-file
        // the reason each controller to be in its own directory is that some
        // controllers have database access and logic in the same folder
        $this->controllerPath = CONTROLLERS_PATH . "/" . $this->controllerName  . "/" . $this->controllerName . '.php';

        // check if controller exists
        if (!file_exists( $this->controllerPath))
            throw new NoSuchControllerException($this->controllerName, "Router.php", 143);
    }

    /**
     * Creates an object of the
     * required controller using
     * string substitution.
     *
     * @throws ApiException, NoSuchControllerException
     * @throws Exception
     */
    private function route()
    {
        // sanitize controller name first
        $this->sanitizeControllerName();

        //then validate if it exist
        $this->constructControllerPath();

        // if file exists include it
        require_once ($this->controllerPath);

        // text substitution
        // @example:
        // $test = new Test();
        $this->instance = new $this->controllerName();

        // prepare rest call for auth-server verification
        $restCall = new RestCall("Curl", $this->fileManager);

        // authorize the controller
        if (!$this->instance->authorize($this->fileManager, $restCall, $this->instance))
            throw new NotAuthorizedException();

        // get the request method
        $method = $this->methodType;

        // invoke method with the right parameter, if provided
        if (!is_array($this->parameters))
            $this->instance->$method($this->parameters);
        else
            call_user_func_array([$this->instance, $this->methodType], $this->parameters);
    }
}