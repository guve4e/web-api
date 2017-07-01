<?php

include(BASE_CLASS_PATH . "/Database.php");
require_once (EXCEPTION_PATH . "/NotAuthorizedException.php");
/**
 * Controller
 *
 * The base controller class. Authentication and NoAuthentication will
 * extend this class.
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library
 */

abstract class Controller extends Base
{
    /**
     * $name
     *
     * The name of the controller
     *
     * @var string $name Name of module class
     */
    protected $name;

    /**
     * $json_data
     *
     * The data sent to the controller
     * via POST,PUT,DELETE methods
     *
     * @var
     */
    protected $json_data;


    /**
     * Controller constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
        // set name at runtime
        $this->name = $this->ref->getName();

    }// end constructor

    /**
     * authorize
     *
     * Checks if the the derived class is:
     * - object
     * - instance of Controller
     *
     * @static
     * @access public
     * @param mixed $controller
     * @throws NotAuthorizedException
     * @return bool
     */
    public static function authorize($controller)
    {
        $authenticated = false;
        //return true;
        if (!isset($_SERVER['HTTP_APITOKEN'])) throw new NotAuthorizedException();
        // get headers
        $token = $_SERVER['HTTP_APITOKEN'];

        // check for the right API Token
        if ($token == "WRCdmach38E2*$%Ghdo@nf#cOBD4fd") $authenticated = true;

        return (is_object($controller) &&  $controller instanceof Controller && $authenticated);
    }

    /**
     * getJsonData
     *
     * Getter for json_data member
     *
     * @return mixed
     */
    public function getJsonData()
    {
        return $this->json_data;
    }

    /**
     * setJsonData
     *
     * Setter for json_data member
     *
     * @return mixed
     */
    public function setJsonData($data)
    {
        $this->json_data = $data;
    }

    /**
     * Abstract GET
     * Has to be overridden
     *
     * @param $id
     * @return mixed
     */
    public abstract function get($id);

    /**
     * Abstract POST
     * Has to be overridden
     *
     * @param $id
     * @return mixed
     */
    public abstract function post($id);

    /**
     * Abstract PUT
     * Has to be overridden
     *
     * @param $id
     * @return mixed
     */
    public abstract function put($id);

    /**
     * Abstract DELETE
     * Has to be overridden
     *
     * @param $id
     * @return mixed
     */
    public abstract function delete($id);

    /**
     * __destruct
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        parent::__destruct();
    }

}// end class

